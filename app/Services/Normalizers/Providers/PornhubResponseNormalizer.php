<?php

namespace App\Services\Normalizers\Providers;

use App\Dtos\ProviderDto;
use App\Interfaces\NormalizerInterface;

class PornhubResponseNormalizer implements NormalizerInterface
{
    public function supportsNormalization(ProviderDto $providerDto): bool
    {
        return $providerDto->getNormalizerType() === get_class($this);
    }

    public function normalize($line): array|bool
    {
        $start = strpos($line, '{"attr');
        if ($start !== false) {
            $end = strpos($line, "\n", $start);
            if ($end > $start) {
                $jsonEncoded = trim(substr($line, $start, $end - $start), ", ");
                $item = json_decode($jsonEncoded, true);
                if (json_last_error() === JSON_ERROR_NONE && \is_array($item)) {
                    return $item;
                }
            }
        }

        return false;
    }
}
