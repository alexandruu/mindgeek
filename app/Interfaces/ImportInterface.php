<?php

namespace App\Interfaces;

interface ImportInterface
{
    public function import();

    public function canImport(string $source): bool;
}
