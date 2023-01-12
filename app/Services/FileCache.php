<?php

namespace App\Services;

use App\Exceptions\FileIsNotAccessibleCacheException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Throwable;

class FileCache extends CacheAbstract
{
    public function get($url)
    {
        $fileName = $this->generateFileName($url);
        $this->saveFileInCache($fileName, $url);
        $this->saveFileFromCacheToDisk($fileName);

        return $this->getFilePathFromDisk($fileName);
    }

    protected function generateFileName($value)
    {
        $extension = pathinfo($value, PATHINFO_EXTENSION);
        return md5($value) . "." . $extension;
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
        if (Storage::disk()->exists($fileName)) {
            return true;
        }

        Storage::disk()->put($fileName, base64_decode(Cache::get($fileName)));

        return true;
    }

    protected function getFilePathFromDisk($fileName)
    {
        return Storage::url($fileName);
    }
}
