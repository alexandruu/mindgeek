<?php

namespace App\Services;

use App\Interfaces\ActorsApi;

abstract class ActorsApiAbstract implements ActorsApi
{
    abstract protected function getCollectionFromEndpoint();

    abstract protected function saveActor($item);
}
