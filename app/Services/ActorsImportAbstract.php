<?php

namespace App\Services;

use App\Interfaces\ActorsImport;
use GuzzleHttp\Client;

abstract class ActorsImportAbstract implements ActorsImport
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
