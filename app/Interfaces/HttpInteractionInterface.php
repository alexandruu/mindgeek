<?php

namespace App\Interfaces;

interface HttpInteractionInterface
{
    public function canProcess(HttpImportInterface | HttpStreamImportInterface $httpImportInterface): bool;

    public function process(HttpImportInterface | HttpStreamImportInterface $httpImportInterface);
}
