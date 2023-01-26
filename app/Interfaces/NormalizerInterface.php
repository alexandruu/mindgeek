<?php

namespace App\Interfaces;

interface NormalizerInterface
{
    public function supportsNormalization($provider): bool;

    public function normalize($line): array|bool;
}