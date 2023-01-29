<?php

namespace App\Services\Import\Caches;

use App\Exceptions\FileAlreadyExistCacheException;
use App\Exceptions\FileIsNotAccessibleCacheException;
use App\Models\Actor;
use App\Models\Url;
use App\Repositories\ActorsRepository;
use App\Services\FileCache;
use Illuminate\Support\Facades\Cache;

class ActorCache
{
    public const PREFIX_FILENAME = 'thumbnails/';

    private FileCache $fileCache;

    public function __construct(FileCache $fileCache)
    {
        $this->fileCache = $fileCache;
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
        $url->url_cache = $this->fileCache->saveFileInCache(self::PREFIX_FILENAME, $url->url);
        $url->save();
    }

    private function clearCacheFor(Actor $actor): void
    {
        Cache::forget(ActorsRepository::keyForGetActorsPaginates());
        Cache::forget(ActorsRepository::keyForGetById($actor->id));
    }
}
