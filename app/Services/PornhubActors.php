<?php

namespace App\Services;

use App\Exceptions\FileIsNotAccessibleCacheException;
use App\Interfaces\ActorsInterface;
use App\Repositories\ActorsRepository;
use Illuminate\Support\Facades\Cache;

class PornhubActors implements ActorsInterface
{
    private FileCache $fileCache;
    private ActorsRepository $actorsRepository;

    public function __construct(FileCache $fileCache, ActorsRepository $actorsRepository)
    {
        $this->fileCache = $fileCache;
        $this->actorsRepository = $actorsRepository;
    }

    public function getAll()
    {
        $actors = $this->actorsRepository->getActorsPaginated();
        foreach ($actors as $actor) {
            $actor->thumbnails->map($this->getCallbackForCachedImages());
        }

        return $actors;
    }

    public function getById($id)
    {
        $actor = $this->actorsRepository->getById(($id));
        $actor->thumbnails->map($this->getCallbackForCachedImages());

        return $actor;
    }

    private function getCallbackForCachedImages()
    {
        return function ($thumbnail) {
            $thumbnail->urls->map(function ($url) use ($thumbnail) {
                try {
                    if ($url->url_cache === null) {
                        $this->updateUrlCache($url);
                        $this->clearCache($thumbnail->actor_id);
                    }
                } catch (FileIsNotAccessibleCacheException $e) {
                }

                $url->url = $url->url_cache;

                return $url;
            });
        };
    }

    private function updateUrlCache($url): void
    {
        $url->url_cache = $this->fileCache->get($url->url);
        $url->save();
    }

    private function clearCache($actorId): void
    {
        Cache::forget(ActorsRepository::keyForGetActorsPaginates());
        Cache::forget(ActorsRepository::keyForGetById($actorId));
    }
}
