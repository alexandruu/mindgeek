<?php

namespace Tests\Unit\Services;

use App\Exceptions\FileIsNotAccessibleCacheException;
use App\Interfaces\StorageInterface;
use App\Services\Caches\FileCache;
use App\Services\Storage\StorageService;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class FileCacheTest extends TestCase
{
    public const CACHE_CONTENT_BASE64_ENCODED = 'dGVzdCBjb250ZW50';
    public const CACHE_CONTENT_BASE64_DECODED = 'test content';

    protected StorageInterface $storageService;
    protected FileCache $service;
    protected $result;

    protected function setUp(): void
    {
        parent::setUp();

        $this->storageService = \Mockery::mock(StorageService::class);
        $this->service = $this->getMockOfFileCache(['generateFileName']);
    }

    public function testSaveFileInCache()
    {
        $this->setExpectationsForCacheAndServiceToSaveAFileInCacheWithSuccess();
        $this->requestToCacheAFile();
    }

    public function testExceptionIsThrownWhenFileIsNotSavedInCache()
    {
        $this->setExceptionExpectation();
        $this->prepareFileCacheToThrowExceptionWhenTringToSaveFileInCache();
        $this->requestToCacheAFile();
    }

    public function testGetFileContentFromCache()
    {
        $this->setExpectationsForCacheToGetFileContentFromCacheWithSuccess();
        $this->requestFileContentFromCache();
        $this->checkIfFileContentIsOk();
    }

    private function setExpectationsForCacheAndServiceToSaveAFileInCacheWithSuccess()
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

    private function requestToCacheAFile()
    {
        $this->service->saveFileInCache(null, "test.jpg");
    }

    private function setExceptionExpectation()
    {
        $this->expectException(FileIsNotAccessibleCacheException::class);
    }

    private function prepareFileCacheToThrowExceptionWhenTringToSaveFileInCache()
    {
        $this->service->shouldReceive('generateFileName')
            ->once();

        Cache::shouldReceive('has')
            ->once()
            ->andReturn(false);

        Cache::shouldReceive('rememberForever')
            ->once()
            ->andThrow(new \Exception());
    }

    private function setExpectationsForCacheToGetFileContentFromCacheWithSuccess()
    {
        Cache::shouldReceive('has')
            ->once()
            ->andReturn(true);

        Cache::shouldReceive('get')
            ->once()
            ->andReturn(self::CACHE_CONTENT_BASE64_ENCODED);
    }

    private function requestFileContentFromCache()
    {
        $this->result = $this->service->getFileContentFromCache('test');
    }

    private function checkIfFileContentIsOk()
    {
        $this->assertEquals(self::CACHE_CONTENT_BASE64_DECODED, $this->result);
    }

    private function getMockOfFileCache(array $methodsToMock = [])
    {
        $className = sprintf(
            'App\Services\Caches\FileCache[%s]',
            implode(',', $methodsToMock)
        );

        return \Mockery::mock($className, [$this->storageService])
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();
    }
}
