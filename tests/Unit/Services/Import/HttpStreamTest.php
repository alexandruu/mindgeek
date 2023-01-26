<?php

namespace Tests\Unit\Services\Import;

use App\Interfaces\HttpStreamImportInterface;
use App\Interfaces\StorageInterface;
use App\Services\Import\HttpInteractionService;
use App\Services\Import\HttpStream;
use App\Services\Import\Providers\PornhubActorsImport;
use App\Services\Storage;
use GuzzleHttp\Client;
use Tests\TestCase;
use Tests\Unit\Services\Import\Providers\PornhubActorsApiTest;

class HttpStreamTest extends TestCase
{
    protected Client $client;
    protected StorageInterface $storage;
    protected HttpStream $service;
    protected HttpStreamImportInterface $provider;
    protected $result;
    protected $body;
    protected $response;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = \Mockery::mock(Client::class);
        $this->storage = \Mockery::mock(Storage::class);
        $this->service = new HttpStream($this->client, $this->storage);
    }

    public function testCanProcessWithCompatibleProvider()
    {
        $this->prepareCompatibleProvider();
        $this->runTheCompatibilityTest();
        $this->checkIfTheProviderIsCompatibleWithHttpStreamStrategy();
    }

    public function testCanProcessWithIncompatibleProvider()
    {
        $this->prepareIncompatibleProvider();
        $this->runTheCompatibilityTest();
        $this->checkIfTheProviderIsIncompatibleWithHttpStreamStrategy();
    }

    public function testProcessWithValidJsonInStorage()
    {
        $this->prepareServiceAndProviderWithValidJsonInStorage();
        $this->makeTheImport();
        $this->checkIfTheExtractedModelHasTheExpectedInformation();
    }

    public function testProcessWithInvalidJsonInStorage()
    {
        $this->prepareServiceAndProviderWithInvalidJsonInStorage();
        $this->makeTheImport();
        $this->checkThatNothingIsImported();
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

    private function prepareCompatibleProvider()
    {
        $this->prepareProviderForCompatibilityCheck(PornhubActorsImport::HTTP_INTERACTION_TYPE);
    }

    private function prepareProviderForCompatibilityCheck($response)
    {
        $this->provider = $this->getMockOfProvider(['getHttpInteractionType']);
        $this->provider->shouldReceive('getHttpInteractionType')
            ->once()
            ->andReturn($response);
    }

    private function runTheCompatibilityTest()
    {
        $this->result = $this->service->canProcess($this->provider);
    }

    private function checkIfTheProviderIsCompatibleWithHttpStreamStrategy()
    {
        $this->assertTrue($this->result);
    }

    private function prepareIncompatibleProvider()
    {
        $this->prepareProviderForCompatibilityCheck('nothing');
    }

    private function checkIfTheProviderIsIncompatibleWithHttpStreamStrategy()
    {
        $this->assertFalse($this->result);
    }

    private function prepareServiceAndProviderWithValidJsonInStorage()
    {
        $this->prepareBodyForImport();
        $this->prepareRequestForImport();
        $this->prepareStorageWithValidJson();
        $this->client->shouldReceive('request')
            ->once()
            ->andReturn($this->response);
        $this->provider = $this->getMockOfProvider(['getEndpoint']);
        $this->service = \Mockery::mock('App\Services\Import\HttpStream', [], [$this->client, $this->storage])
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();
    }

    private function prepareStorageWithValidJson()
    {
        $this->storage->shouldReceive('pointerAppendBinary')
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
            ->andReturn(PornhubActorsApiTest::RESPONSE_LINE)
            ->shouldReceive('isEndOfFile')
            ->once()
            ->andReturn(true);
    }

    private function prepareStorageWithInvalidJson()
    {
        $this->storage->shouldReceive('pointerAppendBinary')
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
            ->andReturn('not a json')
            ->shouldReceive('isEndOfFile')
            ->once()
            ->andReturn(true);
    }

    private function prepareBodyForImport()
    {
        $this->body = \Mockery::mock('GuzzleHttp\Psr7\Stream');
        $this->body->shouldReceive('eof')
            ->once()
            ->andReturn(false)
            ->shouldReceive('read')
            ->once()
            ->shouldReceive('eof')
            ->once()
            ->andReturn(true);
    }

    private function prepareRequestForImport()
    {
        $this->response = \Mockery::mock('Psr\Http\Message\ResponseInterface');
        $this->response->shouldReceive('getBody')
            ->once()
            ->andReturn($this->body);
    }

    private function makeTheImport()
    {
        $generator = $this->service->process($this->provider);
        $this->result = $generator->current();
        $generator->next();
    }

    private function checkIfTheExtractedModelHasTheExpectedInformation()
    {
        $this->assertTrue(\is_array($this->result));
        $this->assertTrue(\is_array($this->result['attributes']));
        $this->assertEquals('Blonde', $this->result['attributes']['hairColor']);
    }

    private function prepareServiceAndProviderWithInvalidJsonInStorage()
    {
        $this->prepareBodyForImport();
        $this->prepareRequestForImport();
        $this->prepareStorageWithInvalidJson();
        $this->client->shouldReceive('request')
            ->once()
            ->andReturn($this->response);
        $this->provider = $this->getMockOfProvider(['getEndpoint']);
        $this->service = \Mockery::mock('App\Services\Import\HttpStream', [], [$this->client, $this->storage])
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();
    }

    private function checkThatNothingIsImported()
    {
        $this->assertNull($this->result);
    }
}
