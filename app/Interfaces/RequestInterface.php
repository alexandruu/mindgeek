<?php

namespace App\Interfaces;

interface RequestInterface
{
    public function request(ProviderInterface $provider);

    public function canRequest(ProviderInterface $provider): bool;
}