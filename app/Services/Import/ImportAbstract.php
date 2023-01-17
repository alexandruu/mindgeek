<?php

namespace App\Services\Import;

use App\Interfaces\ImportInterface;
use GuzzleHttp\Client;

abstract class ImportAbstract implements ImportInterface
{
    protected Client $client;
    protected HttpInteractionService $httpInteraction;

    public function __construct(Client $client, HttpInteractionService $httpInteraction)
    {
        $this->client = $client;
        $this->httpInteraction = $httpInteraction;
    }
}
