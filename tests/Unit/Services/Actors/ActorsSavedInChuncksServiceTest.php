<?php

namespace Tests\Unit\Services\Actors;

class ActorsSavedInChuncksServiceTest extends ActorsSavedInChuncksServiceTestAbstract
{
    public function testWhatHappensOnDestructionOfTheObject()
    {
        $this->expectCacheToBeClearedAndRemainingDataToBeSaved();
    }
}
