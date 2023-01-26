<?php

namespace App\Services\Import\Requests;

use App\Enums\RequestEnum;
use App\Interfaces\ProviderInterface;
use App\Interfaces\RequestInterface;
use App\Interfaces\StorageInterface;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class StreamRequest implements RequestInterface
{
    public const HTTP_BATCH_SIZE_IN_BYTES = 1048576;

    private Client $client;
    private StorageInterface $storage;

    public function __construct(Client $client, StorageInterface $storage)
    {
        $this->client = $client;
        $this->storage = $storage;
    }

    public function canRequest(ProviderInterface $provider): bool
    {
        return $provider->getRequestType() === RequestEnum::STREAM;
    }

    public function request(ProviderInterface $provider)
    {
        $response = $this->makeRequest($provider);
        $filePath = $this->saveResponseInFile($response->getBody());

        $provider->setResponse($filePath);
    }

    private function makeRequest(ProviderInterface $provider): ResponseInterface
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

    private function generateFileName(): string
    {
        return "json_" . date('YmdHis') . ".json";
    }

    private function generateFilePath(): string
    {
        return $this->storage->path($this->generateFileName());
    }
}
