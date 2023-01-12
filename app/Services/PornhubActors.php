<?php

namespace App\Services;

use App\Exceptions\FileIsNotAccessibleCacheException;
use App\Interfaces\Actors;
use App\Models\Actor;

class PornhubActors implements Actors
{
    private FileCache $fileCache;

    public function __construct(FileCache $fileCache)
    {
        $this->fileCache = $fileCache;
    }

    public function getAll()
    {
        $actors = Actor::all();
        foreach ($actors as $actor) {
            $actor->thumbnails->map($this->getCallbackForCachedImages());
        }

        return $actors;
    }

    public function getById($id)
    {
        $actor = Actor::find($id);
        $actor->thumbnails->map($this->getCallbackForCachedImages());

        return $actor;
    }

    private function getCallbackForCachedImages()
    {
        return function ($thumbnail) {
            $thumbnail->urls->map(function ($url) {
                try {
                    $url->url = $this->fileCache->get($url->url);
                } catch (FileIsNotAccessibleCacheException $e) {
                    $url->url = null;
                }

                return $url;
            });
        };
    }
}
