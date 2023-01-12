<?php

namespace App\Services;

use App\Models\Actor;
use Illuminate\Support\Facades\Http;

class PornhubActorsApi extends ActorsApiAbstract
{
    public const ENDPOINT = 'https://www.pornhub.comm/files/json_feed_pornstars.json';
    public const NUMBER_OF_MODELS_TO_IMPORT = 10;

    public function import()
    {
        $collectionOfActors = $this->getCollectionFromEndpoint();
        foreach ($collectionOfActors as $index => $item) {
            $this->saveActor($item);

            if ($this->isLimitRechead($index + 1)) {
                break;
            }
        }

        return true;
    }

    protected function getCollectionFromEndpoint()
    {
        return Http::get(self::ENDPOINT)->collect('items');
    }

    protected function saveActor($item)
    {
        $actor = new Actor();
        $actor->fill($item);
        $actor->save();

        $this->saveThumbnails($actor, $item['thumbnails']);
    }

    private function saveThumbnails($actor, $thumbnails): bool
    {
        foreach ($thumbnails as $thum) {
            $thumbnail = $actor->thumbnails()->create($thum);

            $this->saveUrls($thumbnail, $thum['urls']);
        }

        return true;
    }

    private function saveUrls($thumbnail, $urls): bool
    {
        foreach ($urls as $url) {
            $thumbnail->urls()->create(['url' => $url]);
        }

        return true;
    }

    private function isLimitRechead($current): bool
    {
        return $current == self::NUMBER_OF_MODELS_TO_IMPORT;
    }
}
