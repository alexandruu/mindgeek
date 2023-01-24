<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage as StorageFacade;

class Storage
{
    public const DEFAULT_DRIVER = 'local';

    public function pointerRead($filePath)
    {
        return $this->getFilePointer($filePath, "r");
    }

    public function pointerAppendBinary($filePath, $content)
    {
        $file = $this->getFilePointer($filePath, "a+b");
        fwrite($file, $content);
        fclose($file);
    }

    public function isEndOfFile($file): bool
    {
        return feof($file);
    }

    public function readOneLine($filePointerResource)
    {
        return fgets($filePointerResource);
    }

    public function path($fileName, $driver = self::DEFAULT_DRIVER)
    {
        return StorageFacade::disk($driver)
            ->path($fileName);
    }

    private function getFilePointer($filePath, $mode)
    {
        return fopen($filePath, $mode);
    }
}
