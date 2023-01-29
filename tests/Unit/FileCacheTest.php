<?php

namespace Tests\Unit\Services;

use App\Exceptions\FileIsNotAccessibleCacheException;
use App\Interfaces\StorageInterface;
use App\Services\FileCache;
use App\Services\Storage;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class FileCacheTest extends TestCase
{
    protected StorageInterface $storage;
    protected FileCache $service;
    protected $result;

    protected function setUp(): void
    {
        parent::setUp();

        $this->storage = \Mockery::mock(Storage::class);
        $this->service = $this->getMockOfFileCache(['generateFileName']);
    }

    public function testSaveFileInCache()
    {
        $this->service->shouldReceive('generateFileName')
            ->once();

        $this->service->saveFileInCache(null, "test.jpg");
    }

    // public function testExceptionIsThrownWhenFileIsNotSavedInCache()
    // {
    //     $this->setExceptionExpectation();
    //     $this->prepareFileCacheToThrowExceptionWhenTringToSaveFileInCache();
    //     $this->requestAFile();
    // }

    // public function testEntireFlowWhenRequestingAFile()
    // {
    //     $this->prepareStorageAndFileCache();
    //     $this->requestAFile();
    //     $this->checkIfUrlForFileIsReturned();
    // }

    private function setExceptionExpectation()
    {
        $this->expectException(FileIsNotAccessibleCacheException::class);
    }

    private function prepareFileCacheToThrowExceptionWhenTringToSaveFileInCache()
    {
        $this->service->shouldReceive('generateFileName')
            ->once()
            ->shouldReceive('saveFileInCache')
            ->once()
            ->andThrow(new FileIsNotAccessibleCacheException())
            ->shouldNotReceive('saveFileFromCacheToDisk');
    }

    private function requestAFile()
    {
        $this->result = $this->service->get('test');
    }

    private function prepareStorageAndFileCache()
    {
        $this->storage->shouldReceive('url')
            ->once()
            ->andReturn('test.jpg');

        $this->service->shouldReceive('generateFileName')
            ->once()
            ->shouldReceive('saveFileInCache')
            ->once()
            ->andReturn(true)
            ->shouldReceive('saveFileFromCacheToDisk')
            ->once();
    }

    private function checkIfUrlForFileIsReturned()
    {
        $this->assertEquals('test.jpg', $this->result);
    }

    private function getMockOfFileCache(array $methodsToMock = [])
    {
        $className = sprintf(
            'App\Services\FileCache[%s]',
            implode(',', $methodsToMock)
        );

        return \Mockery::mock($className, [$this->storage])
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();
    }
}
