<?php

namespace Tests\Unit\Services\Http;

use App\Dtos\ProviderDto;
use App\Enums\HttpTypeEnum;
use App\Services\Http\HttpService;
use App\Services\Http\RequestService;
use App\Services\Http\ResponseService;
use Generator;
use Tests\TestCase;


abstract class HttpServiceTestAbstract extends TestCase
{
    protected RequestService $requestService;
    protected ResponseService $responseService;
    protected HttpService $service;
    protected ProviderDto $providerDto;
    protected $result;

    protected function setUp(): void
    {
        parent::setUp();

        $this->requestService = \Mockery::mock(RequestService::class);
        $this->responseService = \Mockery::mock(ResponseService::class);
        $this->service = new HttpService($this->requestService, $this->responseService);
    }

    protected function prepareSceneForHttpStreamInteractionWithSuccess(): void
    {
        $this->prepareProviderForHttpStreamInteractionWithSuccess();
        $this->setExpectationsForHttpStreamInteractionWithSuccess();
    }

    private function prepareProviderForHttpStreamInteractionWithSuccess(): void
    {
        $this->providerDto = new ProviderDto();
        $this->providerDto->setEndpoint('endpoint')
            ->setHttpType(HttpTypeEnum::STREAM)
            ->setNormalizerType('normalizer')
            ->setLimitOfImportedItems(100);
    }

    protected function makeHttpStreamInteraction(): void
    {
        $this->result = $this->service->import($this->providerDto);
    }

    private function setExpectationsForHttpStreamInteractionWithSuccess(): void
    {
        $this->requestService->shouldReceive('request')
            ->once();

        $this->responseService->shouldReceive('import')
            ->once()
            ->andReturn($this->arrayAsGenerator([1, 2, 3]));
    }

    protected function checkIfHttpStreamInteractionIsOk()
    {
        $this->assertInstanceOf(Generator::class, $this->result);
        $this->assertEquals(1, $this->result->current());
        $this->result->next();
        $this->assertEquals(2, $this->result->current());
        $this->result->next();
        $this->assertEquals(3, $this->result->current());
    }

    protected function arrayAsGenerator(array $array)
    {
        foreach ($array as $item) {
            yield $item;
        }
    }

    protected function prepareSceneForHttpSimpleInteractionWithSuccess(): void
    {
        $this->prepareProviderForHttpSimpleInteractionWithSuccess();
        $this->setExpectationsForHttpSimpleInteractionWithSuccess();
    }

    private function prepareProviderForHttpSimpleInteractionWithSuccess(): void
    {
        $this->providerDto = new ProviderDto();
        $this->providerDto->setEndpoint('endpoint')
            ->setHttpType(HttpTypeEnum::SIMPLE);
    }

    private function setExpectationsForHttpSimpleInteractionWithSuccess(): void
    {
        $this->requestService->shouldReceive('request')
            ->once();

        $this->responseService->shouldReceive('import')
            ->once()
            ->andReturn('content body');
    }

    protected function checkIfHttpSimpleInteractionIsOk()
    {
        $this->assertEquals('content body', $this->result);
    }
}
