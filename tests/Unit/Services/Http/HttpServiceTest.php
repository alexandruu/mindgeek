<?php

namespace Tests\Unit\Services\Http;

class HttpServiceTest extends HttpServiceTestAbstract
{
    public function testEntireHttpStreamInteractionWithSuccess()
    {
        $this->prepareSceneForHttpStreamInteractionWithSuccess();
        $this->makeHttpStreamInteraction();
        $this->checkIfHttpStreamInteractionIsOk();
    }

    public function testEntireHttpSimpleInteractionWithSuccess()
    {
        $this->prepareSceneForHttpSimpleInteractionWithSuccess();
        $this->makeHttpStreamInteraction();
    }
}
