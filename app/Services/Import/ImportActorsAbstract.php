<?php

namespace App\Services\Import;

use App\Models\Actor;
use App\Repositories\ActorsRepository;
use Illuminate\Support\Facades\Cache;

abstract class ImportActorsAbstract extends ImportAbstract
{
    protected function saveInformation($information)
    {
        $this->saveActor($information);
    }

    protected function saveActor($item)
    {
        $actor = new Actor();
        $actor->fill($item);
        $actor->save();

        $this->saveThumbnails($actor, $item['thumbnails']);
    }

    protected function saveThumbnails($actor, $thumbnails)
    {
        foreach ($thumbnails as $thum) {
            $thumbnail = $actor->thumbnails()->create($thum);

            $this->saveUrls($thumbnail, $thum['urls']);
        }

        return true;
    }

    protected function saveUrls($thumbnail, $urls)
    {
        foreach ($urls as $url) {
            $thumbnail->urls()->create(['url' => $url]);
        }

        return true;
    }

    protected function clearCache()
    {
        Cache::forget(ActorsRepository::keyForGetActorsPaginates());
    }
}
