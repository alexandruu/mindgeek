<?php

namespace Tests\Unit;

use App\Exceptions\FileIsNotAccessibleCacheException;
use App\Services\FileCache;
use PHPUnit\Framework\TestCase;

class FileCacheTest extends TestCase
{
    public function testExceptionIsThrownWhenFileIsNotSavedInCache()
    {
        $fileCache = \Mockery::mock('App\Services\FileCache[generateFileName,saveFileInCache,saveFileFromCacheToDisk,getFilePathFromDisk]')
            ->shouldAllowMockingProtectedMethods();
        
        $fileCache->shouldReceive('generateFileName')
            ->once()
            ->shouldReceive('saveFileInCache')
            ->once()
            ->andThrow(new FileIsNotAccessibleCacheException())
            ->shouldNotReceive('saveFileFromCacheToDisk')
            ->shouldNotReceive('getFilePathFromDisk');
        
        $this->expectException(FileIsNotAccessibleCacheException::class);

        $result = $fileCache->get('test');
    }

    public function testHappyPath()
    {
        $fileCache = \Mockery::mock('App\Services\FileCache[generateFileName,saveFileInCache,saveFileFromCacheToDisk,getFilePathFromDisk]')
            ->shouldAllowMockingProtectedMethods();
        
        $fileCache->shouldReceive('generateFileName')
            ->once()
            ->shouldReceive('saveFileInCache')
            ->once()
            ->andReturn(true)
            ->shouldReceive('saveFileFromCacheToDisk')
            ->once()            
            ->shouldReceive('getFilePathFromDisk')
            ->once()
            ->andReturn('test.jpg');

        $result = $fileCache->get('test.jpg');

        $this->assertEquals('test.jpg', $result);
    }
}
