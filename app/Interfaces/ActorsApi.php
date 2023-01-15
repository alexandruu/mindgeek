<?php

namespace App\Interfaces;

interface ActorsApi
{
    public function import();

    public function canImport(string $source): bool;
}
