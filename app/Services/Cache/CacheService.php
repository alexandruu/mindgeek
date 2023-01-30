<?php

namespace App\Services\Cache;

use App\Exceptions\FileAlreadyExistCacheException;
use App\Exceptions\FileIsNotAccessibleCacheException;
use App\Exceptions\FileNotFoundCacheException;
use App\Factories\HttpSimpleFactory;
use App\Interfaces\CacheInterface;
use App\Interfaces\StorageInterface;
use Illuminate\Support\Facades\Cache;
use Throwable;

class CacheService implements CacheInterface
{
    protected StorageInterface $storageService;

    public function __construct(StorageInterface $storageService)
    {
        $this->storageService = $storageService;
    }

    public function saveFileInCache($prefixFileName = null, $url): string|FileIsNotAccessibleCacheException
    {
        $fileName = $this->generateFileName($prefixFileName, $url);

        if (!Cache::has($fileName)) {
            try {
                Cache::rememberForever($fileName, function () use ($url) {
                    return base64_encode(HttpSimpleFactory::import(['endpoint' => $url]));
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

    public function forget($key)
    {
        Cache::forget($key);
    }

    public function flush()
    {
        Cache::flush();
    }

    protected function generateFileName($prefixFileName = null, $filePath)
    {
        $extension = $this->storageService->extension($filePath);
        return (strlen($prefixFileName) > 0 ? $prefixFileName : null) . md5($filePath) . "." . $extension;
    }
}
