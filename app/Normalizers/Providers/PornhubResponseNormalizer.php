<?php

namespace App\Normalizers\Providers;

use App\Interfaces\NormalizerInterface;
use App\Services\Import\Providers\PornhubActorsImport;

class PornhubResponseNormalizer implements NormalizerInterface
{
    public function supportsNormalization($provider): bool
    {
        return $provider instanceof PornhubActorsImport;
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
