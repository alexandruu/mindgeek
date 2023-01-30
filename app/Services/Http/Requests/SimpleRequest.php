<?php

namespace App\Services\Http\Requests;

use App\Dtos\ProviderDto;
use App\Enums\HttpTypeEnum;
use App\Interfaces\RequestInterface;
use GuzzleHttp\Client;

class SimpleRequest implements RequestInterface
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function canRequest(ProviderDto $providerDto): bool
    {
        return $providerDto->getHttpType() === HttpTypeEnum::SIMPLE;
    }

    public function request(ProviderDto $providerDto)
    {
        $response = $this->makeRequest($providerDto);

        $providerDto->setResponseBody($response);
    }

    private function makeRequest(ProviderDto $providerDto)
    {
        return $this->client->request('GET', $providerDto->getEndpoint())
            ->getBody()
            ->getContents();
    }
}
