<?php

namespace Tests\Unit;

use App\Services\PornhubActorsImport;
use Tests\TestCase;
use GuzzleHttp\Client;

class PornhubActorsApiTest extends TestCase
{
    public function testImport()
    {
        $client = new Client();
        $pornhubResource = \Mockery::mock('App\Services\PornhubActorsImport[getContentFromEndpoint,saveResponseInFile,saveActors]', [$client])
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $pornhubResource->shouldReceive('getContentFromEndpoint')
            ->once()
            ->shouldReceive('saveResponseInFile')
            ->once()
            ->shouldReceive('saveActors')
            ->once();
        $pornhubResource->import();
    }

    public function testCanImport()
    {
        $client = new Client();
        $pornhubResource = \Mockery::mock('App\Services\PornhubActorsImport[getContentFromEndpoint,saveResponseInFile,saveActors]', [$client])
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $this->assertTrue($pornhubResource->canImport(PornhubActorsImport::ID));
    }
}
