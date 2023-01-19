<?php

namespace Tests\Unit;

use App\Services\Import\HttpInteractionService;
use App\Services\Import\Providers\PornhubActorsImport;
use Tests\TestCase;
use GuzzleHttp\Client;

class PornhubActorsApiTest extends TestCase
{
    public function testImport()
    {
        $client = new Client();
        $httpInteractionService = \Mockery::mock(HttpInteractionService::class);
        $service = \Mockery::mock('App\Services\Import\Providers\PornhubActorsImport[saveInformation]', [$client, $httpInteractionService])
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $httpInteractionService->shouldReceive('import')
            ->once()
            ->andReturn(['text']);

        $service->shouldReceive('saveInformation')
            ->once();
        $service->import();
    }

    public function testCanImport()
    {
        $client = new Client();
        $pornhubResource = \Mockery::mock('App\Services\Import\Providers\PornhubActorsImport[]', [$client])
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $this->assertTrue($pornhubResource->canImport(PornhubActorsImport::ID));
    }
}
