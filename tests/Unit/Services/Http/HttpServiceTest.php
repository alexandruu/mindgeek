<?php

namespace Tests\Unit\Services\Http;

class HttpServiceTest extends HttpServiceTestAbstract
{
    public function testAHttpStreamInteractionWithSuccess()
    {
        $this->expectHttpStreamInteractionWithSuccess();
        $this->makeHttpStreamInteraction();
        $this->checkIfHttpStreamInteractionIsOk();
    }

    public function testAHttpSimpleInteractionWithSuccess()
    {
        $this->expectHttpSimpleInteractionWithSuccess();
        $this->makeHttpStreamInteraction();
        $this->checkIfHttpSimpleInteractionIsOk();
    }
}
