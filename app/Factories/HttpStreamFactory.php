<?php

namespace App\Factories;

use App\Dtos\ProviderDto;
use App\Enums\HttpTypeEnum;
use App\Services\Http\HttpService;

class HttpStreamFactory
{
    public static function import($data)
    {
        $providerDto = new ProviderDto();
        $providerDto->setEndpoint($data['endpoint'])
            ->setHttpType(HttpTypeEnum::STREAM)
            ->setNormalizerType($data['normalizer'])
            ->setLimitOfImportedItems($data['limit']);

        $httpService = app()->make(HttpService::class);
        
        return $httpService->import($providerDto);
    }
}