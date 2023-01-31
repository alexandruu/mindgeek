<?php

namespace Tests\Unit\Services\Actors;

use App\Services\Actors\ActorsSavedInChuncksService;
use App\Services\Cache\CacheService;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

abstract class ActorsSavedInChuncksServiceTestAbstract extends TestCase
{
    protected CacheService $cacheService;
    protected ActorsSavedInChuncksService $service;

    protected function setUp(): void
    {
         parent::setUp();

        $this->queryBuilder = \Mockery::mock(Builder::class);
        $this->cacheService = \Mockery::mock(CacheService::class);
    }

     protected function expectCacheToBeClearedAndRemainingDataToBeSaved(): void
     {
         DB::shouldReceive('table')
             ->andReturn($this->queryBuilder);
         $this->queryBuilder->shouldReceive('insert')
             ->times(3);
         $this->cacheService->shouldReceive('flush')
             ->once();

         $service = new ActorsSavedInChuncksService($this->cacheService);
     }
}
