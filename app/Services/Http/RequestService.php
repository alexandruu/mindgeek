<?php

namespace App\Services\Http;

use App\Interfaces\ProviderInterface;
use App\Interfaces\RequestInterface;

class RequestService
{
    private array $requests;

    public function request(ProviderInterface $provider)
    {
        foreach ($this->requests as $request) {
            if ($request->canRequest($provider)) {
                $request->request($provider);
            }
        }
    }

    public function addRequest(RequestInterface $request)
    {
        $this->requests[] = $request;
    }
}
