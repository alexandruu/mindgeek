<?php

namespace App\Services\Http;

use App\Dtos\ProviderDto;
use App\Interfaces\RequestInterface;

class RequestService
{
    private array $requests;

    public function request(ProviderDto $providerDto)
    {
        foreach ($this->requests as $request) {
            if ($request->canRequest($providerDto)) {
                $request->request($providerDto);
            }
        }
    }

    public function addRequest(RequestInterface $request)
    {
        $this->requests[] = $request;
    }
}
