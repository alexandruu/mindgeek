<?php

namespace App\Services\Http;

use App\Interfaces\ResponseInterface;

class ResponseService
{
    private array $responses;

    public function import($provider)
    {
        foreach ($this->responses as $response) {
            if ($response->canImport($provider)) {
                $response->import($provider);
            }
        }
    }

    public function addResponse(ResponseInterface $response)
    {
        $this->responses[] = $response;
    }
}
