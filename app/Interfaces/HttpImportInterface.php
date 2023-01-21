<?php

namespace App\Interfaces;

interface HttpImportInterface extends ImportInterface
{
    public function getEndpoint();

    public function getHttpInteractionType();
}
