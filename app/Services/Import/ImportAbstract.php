<?php

namespace App\Services\Import;

use App\Interfaces\ImportInterface;
use GuzzleHttp\Client;

abstract class ImportAbstract implements ImportInterface
{
    protected Client $client;
    protected HttpInteractionService $httpInteraction;
    protected $index = 0;

    abstract protected function saveInformation($information);

    abstract protected function clearCache();

    public function __construct(Client $client, HttpInteractionService $httpInteraction)
    {
        $this->client = $client;
        $this->httpInteraction = $httpInteraction;
    }

    public function canImport(string $string): bool
    {
        return $string === static::ID;
    }

    public function import()
    {
        foreach ($this->httpInteraction->import($this) as $information) {
            $this->saveInformation($information);

            if ($this->isLimitReached()) {
                break;
            }
        }

        $this->clearCache();

        return $this->index !== 0;
    }

    protected function isLimitReached(): bool
    {
        return ++$this->index == static::NUMBER_OF_MODELS_TO_IMPORT;
    }
}
