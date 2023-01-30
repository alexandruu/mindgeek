<?php

namespace App\Interfaces;

use App\Dtos\ProviderDto;

interface RequestInterface
{
    public function request(ProviderDto $providerDto);

    public function canRequest(ProviderDto $providerDto): bool;
}