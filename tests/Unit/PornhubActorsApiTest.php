<?php

namespace Tests\Unit;

use App\Services\PornhubActorsApi;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class PornhubActorsApiTest extends TestCase
{
    public function testPopulateMethodMakesHttpRequest()
    {
        $body = [
            'items' => [[
                'name' => 'Test', 
                'thumbnails' => [[
                    'height' => '11'
                ]]
            ]]
        ];
        $pornhubResource = \Mockery::mock('App\Services\PornhubActorsApi[saveActor]')
             ->shouldAllowMockingProtectedMethods();
        Http::fake([PornhubActorsApi::ENDPOINT => Http::response($body, 200, ['server' => 'nginx'])]);
        $pornhubResource->shouldReceive('saveActor')
            ->once();
        $pornhubResource->import();
        Http::assertSentCount(1);
    }
}