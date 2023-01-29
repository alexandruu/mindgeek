<?php

namespace App\Services;

use App\Exceptions\FileAlreadyExistCacheException;
use App\Exceptions\FileIsNotAccessibleCacheException;
use App\Exceptions\FileNotFoundCacheException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Throwable;

class FileCache extends CacheAbstract
{
    public function saveFileInCache($prefixFileName = null, $url): string|FileIsNotAccessibleCacheException
    {
        $fileName = $this->generateFileName($prefixFileName, $url);

        if (!Cache::has($fileName)) {
            try {
                Cache::rememberForever($fileName, function () use ($url) {
                    return base64_encode(Http::get($url)->body());
                });
            } catch (Throwable $e) {
                throw new FileIsNotAccessibleCacheException();
            }

            return $fileName;
        }

        throw new FileAlreadyExistCacheException();
    }

    public function getFileContentFromCache($fileName): string|FileNotFoundCacheException
    {
        if (Cache::has($fileName)) {
            return base64_decode(Cache::get($fileName));
        }

        throw new FileNotFoundCacheException();
    }

    protected function generateFileName($prefixFileName = null, $filePath)
    {
        $extension = $this->storage->extension($filePath);
        return (strlen($prefixFileName) > 0 ? $prefixFileName : null) . md5($filePath) . "." . $extension;
    }
}
