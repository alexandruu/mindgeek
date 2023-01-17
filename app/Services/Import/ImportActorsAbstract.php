<?php

namespace App\Services\Import;

use App\Interfaces\HttpImportInterface;
use App\Models\Actor;

abstract class ImportActorsAbstract extends ImportAbstract implements HttpImportInterface
{
    protected $index = 0;

    public function canImport(string $string): bool
    {
        return $string === static::ID;
    }

    public function import()
    {
        foreach ($this->httpInteraction->import($this) as $actor) {
            $this->saveActor($actor);

            if ($this->isLimitReached()) {
                break;
            }
        }

        return $this->index !== 0;
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

    protected function isLimitReached(): bool
    {
        return ++$this->index == static::NUMBER_OF_MODELS_TO_IMPORT;
    }
}
