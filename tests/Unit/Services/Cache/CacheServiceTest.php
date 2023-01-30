<?php

namespace Tests\Unit\Services\Cache;

class CacheServiceTest extends CacheServiceTestAbstract
{
    public function testSaveFileInCacheWithSuccess()
    {
        $this->setExpectationsForCacheAndServiceToSaveAFileInCacheWithSuccess();
        $this->requestToCacheAFile();
    }

    public function testSaveFileInCacheFailWithExceptionWhenCacheIsNotWorking()
    {
        $this->setExceptionExpectation();
        $this->prepareFileCacheToThrowExceptionWhenTringToSaveFileInCache();
        $this->requestToCacheAFile();
    }

    public function testGetFileContentFromCacheWithSuccess()
    {
        $this->setExpectationsForCacheToGetFileContentFromCacheWithSuccess();
        $this->requestFileContentFromCache();
        $this->checkIfFileContentIsOk();
    }
}
