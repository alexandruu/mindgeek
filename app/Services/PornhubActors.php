<?php

namespace App\Services;

use App\Exceptions\FileIsNotAccessibleCacheException;
use App\Interfaces\ActorsInterface;
use App\Models\Actor;
use App\Models\Url;
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
            if (!$this->isImageCachedFor($actor)) {
                $this->cacheImageFor($actor);
                $this->clearCacheFor($actor);
            }
        }

        return $actors;
    }

    public function getById($id)
    {
        $actor = $this->actorsRepository->getById(($id));
        $this->cacheImagesFor($actor);
        return $actor;
    }

    private function isImageCachedFor(\stdClass $actor): bool
    {
        return !empty($actor->url_cache);
    }

    private function cacheImageFor(\stdClass $actor)
    {
        $actor->url_cache = $this->fileCache->get($actor->url);
        Url::find($actor->url_id)->update(['url_cache' => $actor->url_cache]);
    }

    private function cacheImagesFor(Actor $actor)
    {
        $actor->thumbnails->map(function ($thumbnail) use ($actor) {
            $thumbnail->urls->map(function ($url) use ($actor) {
                try {
                    if ($url->url_cache === null) {
                        $this->updateUrlCache($url);
                        $this->clearCacheFor($actor);
                    }
                } catch (FileIsNotAccessibleCacheException $e) {
                }

                $url->url = $url->url_cache;

                return $url;
            });
        });
    }

    private function updateUrlCache(Url $url): void
    {
        $url->url_cache = $this->fileCache->get($url->url);
        $url->save();
    }

    private function clearCacheFor(\stdClass|Actor $actor): void
    {
        Cache::forget(ActorsRepository::keyForGetActorsPaginates());
        Cache::forget(ActorsRepository::keyForGetById($actor->id));
    }
}
