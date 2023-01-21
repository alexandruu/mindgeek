<?php

namespace App\Interfaces;

interface HttpInteractionInterface
{
    public function canProcess(HttpImportInterface $httpImportInterface): bool;

    public function process(HttpImportInterface $httpImportInterface);
}
