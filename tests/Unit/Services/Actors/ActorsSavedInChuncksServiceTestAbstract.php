<?php

namespace Tests\Unit\Services\Actors;

use App\Services\Actors\ActorsSavedInChuncksService;
use App\Services\Cache\CacheService;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Illuminate\Database\Query\Builder;

abstract class ActorsSavedInChuncksServiceTestAbstract extends TestCase
{
    protected CacheService $cacheService;
    protected ActorsSavedInChuncksService $service;

    protected function setUp(): void
    {
         parent::setUp();

         $this->db = \Mockery::mock(Builder::class);
         $this->cacheService = \Mockery::mock(CacheService::class);
    }

     protected function setExpectationForDatabaseAndCache(): void
     {
         DB::shouldReceive('table')
             ->andReturn($this->db);
         $this->db->shouldReceive('insert')
             ->times(3);
         $this->cacheService->shouldReceive('flush')
             ->once();

         $service = new ActorsSavedInChuncksService($this->cacheService);
     }
}
