<?php

namespace App\Services\Caches;

use App\Interfaces\StorageInterface;

abstract class CacheAbstract
{
    protected StorageInterface $storage;

    abstract public function saveFileInCache($prefixFileName = null, $url);

    abstract public function getFileContentFromCache($fileName);

    abstract protected function generateFileName($prefixFileName = null, $value);

    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }
}
