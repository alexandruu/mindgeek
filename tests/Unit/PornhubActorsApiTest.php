<?php

namespace Tests\Unit;

use App\Services\Import\HttpInteractionService;
use App\Services\Import\Providers\PornhubActorsImport;
use Tests\TestCase;

class PornhubActorsApiTest extends TestCase
{
    public function testImport()
    {
        $httpInteractionService = \Mockery::mock(HttpInteractionService::class);
        $service = $this->getMockOfTestedService(
            $httpInteractionService,
            ['saveInformation','isLimitReached','clearCache']
        );        

        $httpInteractionService->shouldReceive('import')
            ->once()
            ->andReturn(['text']);
        $service->shouldReceive('saveInformation')
            ->once()
            ->shouldReceive('isLimitReached')
            ->once()
            ->shouldReceive('clearCache')
            ->once();

        $service->import();
    }

    public function testCanImport()
    {
        $service = $this->getMockOfTestedServiceOnlyWithMethodsToMock();

        $this->assertTrue($service->canImport(PornhubActorsImport::ID));
    }

    public function testGetEndpoint()
    {
        $service = $this->getMockOfTestedServiceOnlyWithMethodsToMock(['getEndpoint']);

        $this->assertEquals(PornhubActorsImport::ENDPOINT, $service->getEndpoint());
    }

    public function testGetHttpInteractionType()
    {
        $service = $this->getMockOfTestedServiceOnlyWithMethodsToMock(['getHttpInteractionType']);

        $this->assertEquals(PornhubActorsImport::HTTP_INTERACTION_TYPE, $service->getHttpInteractionType());
    }

    public function testGetCallbackForExtractModel()
    {
        $service = $this->getMockOfTestedServiceOnlyWithMethodsToMock(['getCallbackForExtractModel']);

        $this->assertIsCallable($service->getCallbackForExtractModel());
    }

    private function getMockOfTestedServiceOnlyWithMethodsToMock($methodsToMock = [])
    {
        $httpInteractionService = \Mockery::mock(HttpInteractionService::class);

        return $this->getMockOfTestedService(
            $httpInteractionService,
            $methodsToMock
        );
    }

    private function getMockOfTestedService(
        $httpInteractionService,
        array $methodsToMock = []
    ) {
        $className = sprintf(
            'App\Services\Import\Providers\PornhubActorsImport[%s]',
            implode(',', $methodsToMock)
        );

        return \Mockery::mock($className, [$httpInteractionService])
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();
    }
}
