<?php

namespace App\Interfaces;

interface HttpImportInterface
{
    public function getEndpoint();

    public function getHttpInteractionType();

    public function getCallbackForExtractModel(): callable;
}
