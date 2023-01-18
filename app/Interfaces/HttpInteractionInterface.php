<?php

namespace App\Interfaces;

interface HttpInteractionInterface
{
    public function canProcess(HttpImportInterface $httpImportInterface);

    public function process(HttpImportInterface $httpImportInterface);
}