<?php

namespace App\Services\Http\Requests;

use App\Dtos\ProviderDto;
use App\Enums\HttpTypeEnum;
use App\Interfaces\RequestInterface;
use App\Interfaces\StorageInterface;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class StreamRequest implements RequestInterface
{
    public const HTTP_BATCH_SIZE_IN_BYTES = 1048576;

    private Client $client;
    private StorageInterface $storageService;

    public function __construct(Client $client, StorageInterface $storageService)
    {
        $this->client = $client;
        $this->storageService = $storageService;
    }

    public function canRequest(ProviderDto $providerDto): bool
    {
        return $providerDto->getHttpType() === HttpTypeEnum::STREAM;
    }

    public function request(ProviderDto $providerDto)
    {
        $response = $this->makeRequest($providerDto);
        $filePath = $this->saveResponseInFile($response->getBody());

        $providerDto->setResponseBody($filePath);
    }

    private function makeRequest(ProviderDto $providerDto): ResponseInterface
    {
        return $this->client->request('GET', $providerDto->getEndpoint(), [
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
            $this->storageService->pointerAppendBinary($filePath, $line);
        }

        return $filePath;
    }

    private function generateFileName(): string
    {
        return "json_" . date('YmdHis') . ".json";
    }

    private function generateFilePath(): string
    {
        return $this->storageService->path($this->generateFileName());
    }
}
