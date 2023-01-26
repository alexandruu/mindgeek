<?php

namespace App\Interfaces;

interface SaverInterface
{
    public function supportSaver(ProviderInterface $provider);

    public function save($model);

    public function clearCache();
}
