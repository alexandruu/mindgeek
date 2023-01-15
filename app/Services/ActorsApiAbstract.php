<?php

namespace App\Services;

use App\Interfaces\ActorsApi;
use GuzzleHttp\Client;

abstract class ActorsApiAbstract implements ActorsApi
{
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    abstract protected function getContentFromEndpoint();

    abstract protected function saveActors($item);
    
    abstract protected function saveActor($item);
}
