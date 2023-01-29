<?php

namespace App\Services\Savers;

use App\Interfaces\ProviderInterface;
use App\Interfaces\SaverInterface;

class SaverService
{
    private array $savers;

    public function getSaver(ProviderInterface $provider)
    {
        foreach ($this->savers as $saver) {
            if ($saver->supportSaver($provider)) {
                return $saver;
            }
        }
    }

    public function addSaver(SaverInterface $saver)
    {
        $this->savers[] = $saver;
    }
}
