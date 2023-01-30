<?php

namespace App\Services\Http\Responses;

use App\Dtos\ProviderDto;
use App\Enums\HttpTypeEnum;
use App\Interfaces\ResponseInterface;
use App\Services\Normalizers\NormalizeResponseService;

class SimpleResponse implements ResponseInterface
{
    private NormalizeResponseService $normalizeResponseService;

    public function __construct(
        NormalizeResponseService $normalizeResponseService
    ) {
        $this->normalizeResponseService = $normalizeResponseService;
    }

    public function canImport(ProviderDto $providerDto): bool
    {
        return $providerDto->getHttpType() === HttpTypeEnum::SIMPLE;
    }

    public function import(ProviderDto $providerDto)
    {
        $result = $providerDto->getResponseBody();

        if ($this->isANormilizerDefined($providerDto)) {
            $result = $this->normalizeResponseService->normalize($providerDto, $result);
        }
        return $result;
    }

    private function isANormilizerDefined(ProviderDto $providerDto): bool
    {
        return $providerDto->getNormalizerType() !== null;
    }
}
