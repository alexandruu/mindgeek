<?php

namespace App\Services\Actors;

use App\Exceptions\FileAlreadyExistCacheException;
use App\Exceptions\FileIsNotAccessibleCacheException;
use App\Interfaces\CacheInterface;
use App\Models\Actor;
use App\Models\Url;
use App\Repositories\ActorRepository;
use Illuminate\Support\Facades\Cache;

class ActorCache
{
    public const PREFIX_FILENAME = 'thumbnails/';

    private CacheInterface $cacheService;

    public function __construct(CacheInterface $cacheService)
    {
        $this->cacheService = $cacheService;
    }

    public function cacheImagesFor(Actor $actor)
    {
        $actor->thumbnails->map(function ($thumbnail) use ($actor) {
            $thumbnail->urls->map(function ($url) use ($actor) {
                try {
                    if ($url->url_cache === null) {
                        $this->updateUrlCache($url);
                        $this->clearCacheFor($actor);
                    }
                } catch (FileIsNotAccessibleCacheException | FileAlreadyExistCacheException $e) {
                }

                return $url;
            });
        });
    }

    private function updateUrlCache(Url $url): void
    {
        $url->url_cache = $this->cacheService->saveFileInCache(self::PREFIX_FILENAME, $url->url);
        $url->save();
    }

    private function clearCacheFor(Actor $actor): void
    {
        Cache::forget(ActorRepository::keyForGetById($actor->id));
    }
}
