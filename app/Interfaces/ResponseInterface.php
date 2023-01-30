<?php

namespace App\Interfaces;

use App\Dtos\ProviderDto;

interface ResponseInterface
{
    public function canImport(ProviderDto $ProviderDto): bool;

    public function import(ProviderDto $ProviderDto);
}
