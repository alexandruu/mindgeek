<?php

namespace App\Services;

use App\Models\Actor;
use Illuminate\Support\Facades\Storage;

class PornhubActorsImport extends ActorsImportAbstract
{
    public const ID = 'pornhub.actors';
    public const ENDPOINT = 'https://www.pornhub.com/files/json_feed_pornstars.json';
    public const NUMBER_OF_MODELS_TO_IMPORT = 15;
    public const HTTP_BATCH_SIZE_IN_BYTES = 1048576;

    private $index = 0;

    public function import()
    {
        $response = $this->getContentFromEndpoint();
        $fileName = $this->saveResponseInFile($response);

        return $this->saveActors($fileName);
    }

    public function canImport(string $string): bool
    {
        return $string === self::ID;
    }

    protected function getContentFromEndpoint()
    {
        return $this->client->request('GET', self::ENDPOINT, [
            'stream' => true,
            'headers' => [
                'Accept' => 'application/json',
            ],
        ]);
    }

    protected function saveResponseInFile($response)
    {
        $body = $response->getBody();
        $fileName = "json" . date('YmdHis') . ".json";

        while (!$body->eof()) {
            $line = $body->read(self::HTTP_BATCH_SIZE_IN_BYTES);
            $file = fopen(Storage::disk()->path($fileName), "a+b");
            fwrite($file, $line);
            fclose($file);
        }

        return $fileName;
    }

    protected function saveActors($fileName): bool
    {
        $handle = fopen(Storage::disk()->path($fileName), 'r');
        while (!feof($handle)) {
            $line = fgets($handle);

            if ($item = $this->getActor($line)) {
                $this->saveActor($item);

                if ($this->isLimitReached()) {
                    break;
                }
            }
        }

        return $this->index !== 0;
    }

    private function getActor($line): array|bool
    {
        $start = strpos($line, '{"attr');
        if ($start) {
            $end = strpos($line, "\n", $start) - 1;

            if ($end > $start) {
                $jsonEncoded = substr($line, $start, $end - $start);
                $item = json_decode($jsonEncoded, true);

                if (json_last_error() === JSON_ERROR_NONE && \is_array($item)) {
                    return $item;
                }
            }
        }

        return false;
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

    private function isLimitReached(): bool
    {
        return ++$this->index == self::NUMBER_OF_MODELS_TO_IMPORT;
    }
}
