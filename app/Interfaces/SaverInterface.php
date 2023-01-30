<?php

namespace App\Interfaces;

interface SaverInterface
{
    public function save($model);

    public function clearCache();
}
