<?php

namespace App\Factories;

use App\Dtos\ProviderDto;
use App\Enums\HttpTypeEnum;
use App\Services\Http\HttpService;

class HttpSimpleFactory
{
    public static function import($data)
    {
        $providerDto = new ProviderDto();
        $providerDto->setEndpoint($data['endpoint'])
            ->setHttpType(HttpTypeEnum::SIMPLE);

        $httpService = app()->make(HttpService::class);
        
        return $httpService->import($providerDto);
    }
}