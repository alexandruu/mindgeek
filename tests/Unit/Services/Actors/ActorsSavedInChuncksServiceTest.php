<?php

namespace Tests\Unit\Services\Actors;

class ActorsSavedInChuncksServiceTest extends ActorsSavedInChuncksServiceTestAbstract
{

    public function testEnsureThatDestructMethodMakesAFlushInDatabaseAndMakesAClearCache()
    {
        $this->setExpectationForDatabaseAndCache();
    }
}
