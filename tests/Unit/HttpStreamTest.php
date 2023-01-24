<?php

namespace Tests\Unit;

use App\Services\Import\HttpInteractionService;
use App\Services\Import\HttpStream;
use App\Services\Import\Providers\PornhubActorsImport;
use App\Services\Storage;
use GuzzleHttp\Client;
use Tests\TestCase;

class HttpStreamTest extends TestCase
{
    protected Client $client;
    protected Storage $storage;
    protected HttpStream $service;

    protected function setUp(): void
    {
        parent::setUp();
    
        $this->client = \Mockery::mock(Client::class);
        $this->storage = \Mockery::mock(Storage::class);
        $this->service = new HttpStream($this->client, $this->storage);
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

    public function testProcess()
    {
        $storage = \Mockery::mock('App\Services\Storage');
        $storage->shouldReceive('pointerAppendBinary')
            ->once()
            ->shouldReceive('path')
            ->once()
            ->andReturn('test')
            ->shouldReceive('pointerRead')
            ->once()
            ->shouldReceive('isEndOfFile')
            ->once()
            ->andReturn(false)
            ->shouldReceive('readOneLine')
            ->once()
            ->shouldReceive('isEndOfFile')
            ->once()
            ->andReturn(true);
        $service = \Mockery::mock('App\Services\Import\HttpStream', [], [$this->client, $storage])
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();        
        $body = \Mockery::mock('GuzzleHttp\Psr7\Stream');
        $body->shouldReceive('eof')
            ->once()
            ->andReturn(false)
            ->shouldReceive('read')
            ->once()
            ->andReturn(PornhubActorsApiTest::RESPONSE_LINE)
            ->shouldReceive('eof')
            ->once()
            ->andReturn(true);
        $response = \Mockery::mock('Psr\Http\Message\ResponseInterface');
        $response->shouldReceive('getBody')
            ->once()
            ->andReturn($body);
        $this->client->shouldReceive('request')
            ->once()
            ->andReturn($response);
        $provider = $this->getMockOfProvider();

        $result = $service->process($provider);
        $result->next();
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