<?php

namespace App\Interfaces;

use App\Dtos\ProviderDto;

interface NormalizerInterface
{
    public function supportsNormalization(ProviderDto $providerDto);

    public function normalize($line): array|bool;
}
