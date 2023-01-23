<?php

namespace App\Services\Import;

use App\Enums\HttpInteractionsEnum;
use App\Interfaces\HttpImportInterface;
use Illuminate\Support\Facades\Storage;

class HttpStream extends HttpInteractionAbstract
{
    public const HTTP_BATCH_SIZE_IN_BYTES = 1048576;

    public function canProcess(HttpImportInterface $provider): bool
    {
        return $provider->getHttpInteractionType() === HttpInteractionsEnum::STREAM;
    }

    public function process(HttpImportInterface $provider)
    {
        $response = $this->makeRequest($provider);
        $filePath = $this->saveResponseInFile($response);

        return $this->getModels($filePath, $provider);
    }

    private function makeRequest($provider)
    {
        return $this->client->request('GET', $provider->getEndpoint(), [
            'stream' => true,
            'headers' => [
                'Accept' => 'application/json',
            ],
        ]);
    }

    private function saveResponseInFile($response): string
    {
        $body = $response->getBody();
        $filePath = Storage::disk()->path("json" . date('YmdHis') . ".json");

        while (!$body->eof()) {
            $line = $body->read(self::HTTP_BATCH_SIZE_IN_BYTES);
            $file = fopen($filePath, "a+b");
            fwrite($file, $line);
            fclose($file);
        }

        return $filePath;
    }

    private function getModels($filePath, $provider)
    {
        $handle = fopen($filePath, 'r');
        while (!feof($handle)) {
            $line = fgets($handle);

            if ($item = $provider->getCallbackForExtractModel()($line)) {
                yield $item;
            }
        }
    }
}
