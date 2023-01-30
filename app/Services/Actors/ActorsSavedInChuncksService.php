<?php

namespace App\Services\Actors;

use App\Services\Cache\CacheService;
use Illuminate\Support\Facades\DB;

class ActorsSavedInChuncksService
{
    public const CHUNCK_SIZE_FOR_PERSIST = 100;

    private CacheService $cacheService;
    private $counter = 0;
    private array $actors = [];
    private array $thumbnails = [];
    private array $urls = [];

    public function __construct(CacheService $cacheService)
    {
        $this->cacheService = $cacheService;
    }

    public function __destruct()
    {
        $this->flush();
        $this->cacheService->flush();
    }

    public function save($information)
    {
        $actor = $this->saveActor($information);
        $this->saveThumbnails($actor, $information['thumbnails']);
        $this->updateCounter();
        $this->flushChunck();
    }

    private function saveActor($information)
    {
        $actor = [
            'id' => $this->generateActorId($information),
            'name' => $information['name'],
            'license' => $information['license'],
            'link' => $information['link'],
        ];

        array_push($this->actors, $actor);

        return $actor;
    }

    private function saveThumbnails($actor, $thumbnails): void
    {
        foreach ($thumbnails as $thumbnail_) {
            $thumbnail = [
                'id' => $this->generateThumbnailId($actor, $thumbnail_),
                'actor_id' => $actor['id'],
                'height' => $thumbnail_['height'],
                'width' => $thumbnail_['width'],
                'type' => $thumbnail_['type'],
            ];

            array_push($this->thumbnails, $thumbnail);

            $this->saveUrls($thumbnail, $thumbnail_['urls']);
        }
    }

    private function saveUrls($thumbnail, $urls): void
    {
        foreach ($urls as $url_) {
            $url = [
                'thumbnail_id' => $thumbnail['id'],
                'url' => $url_
            ];

            array_push($this->urls, $url);
        }
    }

    private function generateActorId($information)
    {
        return md5($information['id'] . $information['name']);
    }

    private function generateThumbnailId($actor, $thumbnail)
    {
        return md5($actor['id'] . $thumbnail['type']);
    }

    private function updateCounter(): void
    {
        $this->counter++;
    }

    private function flushChunck()
    {
        if ($this->counter % self::CHUNCK_SIZE_FOR_PERSIST === 0) {
            $this->flush();
        }
    }

    private function flush()
    {
        DB::table('actors')->insert($this->actors);
        DB::table('thumbnails')->insert($this->thumbnails);
        DB::table('urls')->insert($this->urls);
        $this->actors = [];
        $this->thumbnails = [];
        $this->urls = [];
    }
}
