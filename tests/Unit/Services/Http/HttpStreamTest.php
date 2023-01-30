<?php

namespace Tests\Unit\Services\Http;

class HttpStreamTest extends HttpStreamTestAbstract
{
    public function testEntireHttpStreamInteractionWithSuccess()
    {
        $this->prepareSceneForHttpStreamInteractionWithSuccess();
        $this->makeHttpStreamInteractionWithSuccess();
        $this->checkIfHttpStreamInteractionIsOk();
    }
}
