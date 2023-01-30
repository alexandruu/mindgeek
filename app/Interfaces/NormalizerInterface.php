<?php

namespace App\Interfaces;

interface NormalizerInterface
{
    public function normalize($line): array|bool;
}