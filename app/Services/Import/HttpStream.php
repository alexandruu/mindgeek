<?php

namespace App\Services\Import;

use App\Enums\HttpInteractionsEnum;
use App\Interfaces\HttpImportInterface;
use App\Services\Storage;
use GuzzleHttp\Client;
use Psr\Http\Message\StreamInterface;

class HttpStream extends HttpInteractionAbstract
{
    public const HTTP_BATCH_SIZE_IN_BYTES = 1048576;

    private Storage $storage;

    public function __construct(Client $client, Storage $storage)
    {
        parent::__construct($client);

        $this->storage = $storage;
    }

    public function canProcess(HttpImportInterface $provider): bool
    {
        return $provider->getHttpInteractionType() === HttpInteractionsEnum::STREAM;
    }

    public function process(HttpImportInterface $provider)
    {
        $response = $this->makeRequest($provider);
        $filePath = $this->saveResponseInFile($response->getBody());

        return $this->extractModels($filePath, $provider->getCallbackForExtractModel());
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

    private function saveResponseInFile(StreamInterface $body): string
    {
        $filePath = $this->generateFilePath();

        while (!$body->eof()) {
            $line = $body->read(self::HTTP_BATCH_SIZE_IN_BYTES);
            $this->storage->pointerAppendBinary($filePath, $line);
        }

        return $filePath;
    }

    private function extractModels($filePath, callable $callback)
    {
        $filePointer = $this->storage->pointerRead($filePath);
        while (!$this->storage->isEndOfFile($filePointer)) {
            $line = $this->storage->readOneLine($filePointer);
            if ($item = $callback($line)) {
                yield $item;
            }
        }
    }

    private function generateFileName(): string
    {
        return "json_" . date('YmdHis') . ".json";
    }

    private function generateFilePath(): string
    {
        return $this->storage->path($this->generateFileName());
    }
}
