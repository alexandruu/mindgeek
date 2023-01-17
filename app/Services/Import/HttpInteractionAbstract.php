<?php

namespace App\Services\Import;

use App\Interfaces\HttpInteractionInterface;
use GuzzleHttp\Client;

abstract class HttpInteractionAbstract implements HttpInteractionInterface
{
    protected Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }
}
