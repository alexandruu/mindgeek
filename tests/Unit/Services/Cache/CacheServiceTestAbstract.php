<?php

namespace Tests\Unit\Services\Cache;

use Illuminate\Support\Facades\Cache;
use App\Exceptions\FileIsNotAccessibleCacheException;
use App\Interfaces\CacheInterface;
use App\Interfaces\StorageInterface;
use App\Services\Storage\StorageService;
use Tests\TestCase;

abstract class CacheServiceTestAbstract extends TestCase
{
    public const CACHE_CONTENT_BASE64_ENCODED = 'dGVzdCBjb250ZW50';
    public const CACHE_CONTENT_BASE64_DECODED = 'test content';

    protected StorageInterface $storageService;
    protected CacheInterface $service;
    protected $result;

    protected function setUp(): void
    {
        parent::setUp();

        $this->storageService = \Mockery::mock(StorageService::class);
        $this->service = $this->getMockOfFileCache(['generateFileName']);
    }

    protected function expectToSaveAFileInCacheWithSuccess()
    {
        $this->service->shouldReceive('generateFileName')
            ->once()
            ->andReturn('test');

        Cache::shouldReceive('has')
            ->once()
            ->andReturn(false);

        Cache::shouldReceive('rememberForever')
            ->once();
    }

    protected function requestToCacheAFile()
    {
        $this->service->saveFileInCache(null, "test.jpg");
    }

    protected function expectFileIsNotAccessibleCacheException()
    {
        $this->expectException(FileIsNotAccessibleCacheException::class);

        $this->service->shouldReceive('generateFileName')
            ->once();

        Cache::shouldReceive('has')
            ->once()
            ->andReturn(false);

        Cache::shouldReceive('rememberForever')
            ->once()
            ->andThrow(new \Exception());
    }

    protected function expectToGetFileContentFromCacheWithSuccess()
    {
        Cache::shouldReceive('has')
            ->once()
            ->andReturn(true);

        Cache::shouldReceive('get')
            ->once()
            ->andReturn(self::CACHE_CONTENT_BASE64_ENCODED);
    }

    protected function requestFileContentFromCache()
    {
        $this->result = $this->service->getFileContentFromCache('test');
    }

    protected function checkIfFileContentIsOk()
    {
        $this->assertEquals(self::CACHE_CONTENT_BASE64_DECODED, $this->result);
    }

    protected function getMockOfFileCache(array $methodsToMock = [])
    {
        $className = sprintf(
            'App\Services\Cache\CacheService[%s]',
            implode(',', $methodsToMock)
        );

        return \Mockery::mock($className, [$this->storageService])
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();
    }
}
