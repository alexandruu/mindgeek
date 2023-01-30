<?php

namespace App\Services\Normalizers;

use App\Dtos\ProviderDto;
use App\Interfaces\NormalizerInterface;

class NormalizeResponseService
{
    private array $normalizers;

    public function normalize(ProviderDto $providerDto, $content)
    {
        foreach ($this->normalizers as $normalizer) {
            if ($normalizer->supportsNormalization($providerDto)) {
                return $normalizer->normalize($content);
            }
        }
    }

    public function addNormalizer(NormalizerInterface $normalizer)
    {
        $this->normalizers[] = $normalizer;
    }
}
