<?php

namespace App\Interfaces;

interface CacheInterface
{
    public function saveFileInCache($prefixFileName, $url);

    public function getFileContentFromCache($fileName);

    public function forget($key);
    
    public function flush();
}