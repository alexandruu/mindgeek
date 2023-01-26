<?php

namespace App\Interfaces;

use App\Enums\CategoryEnum;

interface ProviderInterface
{
    public function getEndpoint();

    public function getCategory();

    public function getRequestType();

    public function getResponse(): string;

    public function setResponse(string $response);

    public function hasCategory(CategoryEnum $categoryEnum): bool;
}