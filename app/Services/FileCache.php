<?php

namespace App\Services;

use App\Exceptions\FileIsNotAccessibleCacheException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Throwable;

class FileCache extends CacheAbstract
{
    public function get($url)
    {
        $fileName = $this->generateFileName($url);
        $this->saveFileInCache($fileName, $url);
        $this->saveFileFromCacheToDisk($fileName);

        return $this->storage->url($fileName);
    }

    protected function generateFileName($filePath)
    {
        $extension = $this->storage->extension($filePath);
        return md5($filePath) . "." . $extension;
    }

    protected function saveFileInCache($fileName, $url)
    {
        if (Cache::has($fileName)) {
            return true;
        }

        try {
            Cache::rememberForever($fileName, function () use ($url) {
                return base64_encode(Http::get($url)->body());
            });
        } catch (Throwable $e) {
            throw new FileIsNotAccessibleCacheException();
        }

        return true;
    }

    protected function saveFileFromCacheToDisk($fileName)
    {
        if ($this->storage->exists($fileName)) {
            return true;
        }

        $content = $this->getFileContentFromCache($fileName);

        return $this->storage->put($fileName, $content);
    }

    protected function getFileContentFromCache($fileName)
    {
        return base64_decode(Cache::get($fileName));
    }
}
