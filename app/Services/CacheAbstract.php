<?php

namespace App\Services;

use App\Interfaces\StorageInterface;

abstract class CacheAbstract
{
    protected StorageInterface $storage;

    abstract public function get($url);

    abstract protected function generateFileName($value);

    abstract protected function saveFileInCache($fileName, $url);

    abstract protected function saveFileFromCacheToDisk($fileName);

    abstract protected function getFileContentFromCache($fileName);

    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }
}
