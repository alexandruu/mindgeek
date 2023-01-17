<?php

namespace App\Interfaces;

interface HttpInteractionInterface
{
    public function canProcess(HttpImportInterface $httpImportInterface);

    public function getResponse(HttpImportInterface $httpImportInterface);
}