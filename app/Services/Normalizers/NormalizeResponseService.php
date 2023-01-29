<?php

namespace App\Services\Normalizers;

use App\Interfaces\NormalizerInterface;
use App\Interfaces\ProviderInterface;

class NormalizeResponseService
{
    private array $normalizers;

    public function normalize(ProviderInterface $provider, $content)
    {
        foreach ($this->normalizers as $normalizer) {
            if ($normalizer->supportsNormalization($provider)) {
                return $normalizer->normalize($content);
            }
        }
    }

    public function addNormalizer(NormalizerInterface $normalizer)
    {
        $this->normalizers[] = $normalizer;
    }
}
