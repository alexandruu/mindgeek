<?php

namespace App\Interfaces;

interface StorageInterface
{
    public function pointerRead($filePath);

    public function pointerAppendBinary($filePath, $content);

    public function isEndOfFile($file);

    public function readOneLine($filePointer);

    public function path($fileName, $driver = null);

    public function exists($fileName, $driver = null);

    public function put($fileName, $content, $driver = null);

    public function url($fileName);

    public function extension($filePath);
}
