<?php

namespace App\Services\Actors\Providers;

use App\Enums\CategoryEnum;
use App\Enums\RequestEnum;
use App\Interfaces\ProviderInterface;

class PornhubActorsImport implements ProviderInterface
{
    public const ID = 'pornhub.actors';
    public const CATEGORY = CategoryEnum::ACTORS;
    public const REQUEST_TYPE = RequestEnum::STREAM;
    public const ENDPOINT = 'https://www.pornhub.com/files/json_feed_pornstars.json';
    public const NUMBER_OF_MODELS_TO_IMPORT = 15000;

    private string $response;

    public function hasCategory(CategoryEnum $category): bool
    {
        return $this->getCategory() === $category;
    }

    public function getEndpoint()
    {
        return self::ENDPOINT;
    }

    public function getCategory()
    {
        return self::CATEGORY;
    }

    public function getRequestType()
    {
        return self::REQUEST_TYPE;
    }

    public function getResponse(): string
    {
        return $this->response;
    }

    public function setResponse(string $response): self
    {
        $this->response = $response;
        return $this;
    }
}
