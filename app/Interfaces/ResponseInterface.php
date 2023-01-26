<?php

namespace App\Interfaces;

interface ResponseInterface
{
    public function canImport(ProviderInterface $provider): bool;

    public function import(ProviderInterface $provider);
}
