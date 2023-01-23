<?php

namespace Tests\Unit;

use App\Services\Import\HttpInteractionService;
use App\Services\Import\HttpStream;
use App\Services\Import\Providers\PornhubActorsImport;
use GuzzleHttp\Client;
use Tests\TestCase;

class HttpStreamTest extends TestCase
{
    protected Client $client;
    protected HttpStream $service;

    protected function setUp(): void
    {
        parent::setUp();
    
        $this->client = new Client();
        $this->service = new HttpStream($this->client);
    }
    
    public function testCanProcess()
    {
        $provider = $this->getMockOfProvider(['getHttpInteractionType']);

        $provider->shouldReceive('getHttpInteractionType')
            ->once()
            ->andReturn(PornhubActorsImport::HTTP_INTERACTION_TYPE);

        $this->assertTrue($this->service->canProcess($provider));
    }

    public function testCanProcessShouldFail()
    {
        $provider = $this->getMockOfProvider(['getHttpInteractionType']);

        $provider->shouldReceive('getHttpInteractionType')
            ->once()
            ->andReturn('nothing');

        $this->assertFalse($this->service->canProcess($provider));
    }

    private function getMockOfProvider(array $methodsToMock = [])
    {
        $httpInteractionService = \Mockery::mock(HttpInteractionService::class);
        $className = sprintf(
            'App\Services\Import\Providers\PornhubActorsImport[%s]',
            implode(',', $methodsToMock)
        );
        
        return \Mockery::mock($className, [$httpInteractionService])
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();
    }
}