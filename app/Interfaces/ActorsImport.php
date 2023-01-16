<?php

namespace App\Interfaces;

interface ActorsImport
{
    public function import();

    public function canImport(string $source): bool;
}
