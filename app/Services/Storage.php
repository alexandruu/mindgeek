<?php

namespace App\Services;

use App\Interfaces\StorageInterface;
use Illuminate\Support\Facades\Storage as StorageFacade;

class Storage implements StorageInterface
{
    public const DEFAULT_DRIVER = 'public';

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

    public function readOneLine($filePointer)
    {
        return fgets($filePointer);
    }

    public function path($fileName, $driver = self::DEFAULT_DRIVER)
    {
        return StorageFacade::disk($driver)
            ->path($fileName);
    }

    public function exists($fileName, $driver = self::DEFAULT_DRIVER)
    {
        return StorageFacade::disk($driver)
            ->exists($fileName);
    }

    public function put($fileName, $content, $driver = self::DEFAULT_DRIVER)
    {
        return StorageFacade::disk($driver)
            ->put($fileName, $content);
    }

    public function url($fileName)
    {
        return StorageFacade::url($fileName);
    }

    public function extension($filePath)
    {
        return pathinfo($filePath, PATHINFO_EXTENSION);
    }

    private function getFilePointer($filePath, $mode)
    {
        return fopen($filePath, $mode);
    }
}
