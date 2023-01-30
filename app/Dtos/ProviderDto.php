<?php

namespace App\Dtos;

use App\Enums\HttpTypeEnum;

class ProviderDto
{
    private string $endpoint;
    private HttpTypeEnum $httpType;
    private ?string $normalizerType = null;
    private string $responseBody;
    private int $limitOfImportedItems;

    public function getEndpoint(): string
    {
        return $this->endpoint;
    }

    public function setEndpoint($endpoint): self
    {
        $this->endpoint = $endpoint;
        return $this;
    }

    public function getHttpType(): HttpTypeEnum
    {
        return $this->httpType;
    }

    public function setHttpType(HttpTypeEnum $httpType): self
    {
        $this->httpType = $httpType;
        return $this;
    }

    public function getNormalizerType(): ?string
    {
        return $this->normalizerType;
    }

    public function setNormalizerType($normalizerType): self
    {
        $this->normalizerType = $normalizerType;
        return $this;
    }

    public function getResponseBody(): string
    {
        return $this->responseBody;
    }

    public function setResponseBody($responseBody): self
    {
        $this->responseBody = $responseBody;
        return $this;
    }

    public function getLimitOfImportedItems(): int
    {
        return $this->limitOfImportedItems;
    }

    public function setLimitOfImportedItems($limitOfImportedItems): self
    {
        $this->limitOfImportedItems = $limitOfImportedItems;
        return $this;
    }
}
