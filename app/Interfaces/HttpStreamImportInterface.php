<?php

namespace App\Interfaces;

interface HttpStreamImportInterface extends HttpImportInterface
{
    public function getCallbackForExtractModel(): callable;
}
