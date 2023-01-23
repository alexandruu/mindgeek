<?php

namespace Tests\Unit;

use App\Services\Import\HttpInteractionService;
use App\Services\Import\Providers\PornhubActorsImport;
use Tests\TestCase;

class PornhubActorsApiTest extends TestCase
{
    public const RESPONSE_LINE = '{"attributes":{"hairColor":"Blonde","ethnicity":"White","tattoos":true,"piercings":true,"breastSize":34,"breastType":"A","gender":"female","orientation":"straight","age":41,"stats":{"subscriptions":5430,"monthlySearches":705700,"views":436474,"videosCount":54,"premiumVideosCount":31,"whiteLabelVideoCount":46,"rank":4461,"rankPremium":4569,"rankWl":4326}},"id":2,"name":"Aaliyah Jolie","license":"REGULAR","wlStatus":"1","aliases":["Aliyah Julie","Aliyah Jolie","Aaliyah","Macy"],"link":"https:\/\/www.pornhub.com\/pornstar\/aaliyah-jolie","thumbnails":[{"height":344,"width":234,"type":"pc","urls":["https:\/\/di.phncdn.com\/pics\/pornstars\/000\/000\/002\/(m=lciuhScOb_c)(mh=5Lb6oqzf58Pdh9Wc)thumb_22561.jpg"]},{"height":344,"width":234,"type":"mobile","urls":["https:\/\/di.phncdn.com\/pics\/pornstars\/000\/000\/002\/(m=lciuhScOb_c)(mh=5Lb6oqzf58Pdh9Wc)thumb_22561.jpg"]},{"height":344,"width":234,"type":"tablet","urls":["https:\/\/di.phncdn.com\/pics\/pornstars\/000\/000\/002\/(m=lciuhScOb_c)(mh=5Lb6oqzf58Pdh9Wc)thumb_22561.jpg"]}]},' . "\n";

    public function testImport()
    {
        $httpInteractionService = \Mockery::mock(HttpInteractionService::class);
        $service = $this->getMockOfTestedService(
            $httpInteractionService,
            ['saveInformation', 'isLimitReached', 'clearCache']
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
        $service = $this->getMockOfTestedServiceOnlyWithMethodsToMock();
        $callback = $service->getCallbackForExtractModel();
        $result = $callback(self::RESPONSE_LINE);

        $this->assertIsCallable($callback);
        $this->assertTrue(\is_array($result));
        $this->assertTrue(\is_array($result['attributes']));
        $this->assertEquals('Blonde', $result['attributes']['hairColor']);
    }

    public function testWithBadJsonCallbackForExtractModelShouldReturnFalse()
    {
        $service = $this->getMockOfTestedServiceOnlyWithMethodsToMock();
        $callback = $service->getCallbackForExtractModel();
        $badJson = '[]';

        $this->assertFalse($callback($badJson));
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
