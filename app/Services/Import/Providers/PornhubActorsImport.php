<?php

namespace App\Services\Import\Providers;

use App\Enums\HttpInteractionsEnum;
use App\Services\Import\ImportActorsAbstract;

class PornhubActorsImport extends ImportActorsAbstract
{
    public const ID = 'pornhub.actors';
    public const ENDPOINT = 'https://www.pornhub.com/files/json_feed_pornstars.json';
    public const NUMBER_OF_MODELS_TO_IMPORT = 100;
    public const HTTP_ITERACTION_TYPE = HttpInteractionsEnum::STREAM;

    public function getEndpoint()
    {
        return self::ENDPOINT;
    }

    public function getHttpInteractionType()
    {
        return self::HTTP_ITERACTION_TYPE->value;
    }

    public function getCallbackForExtractModel(): callable
    {
        return function ($line) {
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
        };
    }
}
