<?php

namespace App\Services\Http;

use App\Dtos\ProviderDto;
use App\Interfaces\ResponseInterface;

class ResponseService
{
    private array $responses;

    public function import(ProviderDto $providerDto)
    {
        foreach ($this->responses as $response) {
            if ($response->canImport($providerDto)) {
                return $response->import($providerDto);
            }
        }
    }

    public function addResponse(ResponseInterface $response)
    {
        $this->responses[] = $response;
    }
}
