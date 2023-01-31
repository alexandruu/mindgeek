<?php

namespace Tests\Unit\Services\Cache;

class CacheServiceTest extends CacheServiceTestAbstract
{
    public function testSaveFileInCacheWithSuccess()
    {
        $this->expectToSaveAFileInCacheWithSuccess();
        $this->requestToCacheAFile();
    }

    public function testSaveFileInCacheFailWithExceptionWhenCacheIsNotWorking()
    {
        $this->expectFileIsNotAccessibleCacheException();
        $this->requestToCacheAFile();
    }

    public function testGetFileContentFromCacheWithSuccess()
    {
        $this->expectToGetFileContentFromCacheWithSuccess();
        $this->requestFileContentFromCache();
        $this->checkIfFileContentIsOk();
    }
}
