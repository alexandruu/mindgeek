<?php

namespace App\Services;

abstract class CacheAbstract
{
    abstract public function get($url);

    abstract protected function generateFileName($value);

    abstract protected function saveFileInCache($fileName, $url);

    abstract protected function saveFileFromCacheToDisk($fileName);

    abstract protected function getFilePathFromDisk($fileName);
}