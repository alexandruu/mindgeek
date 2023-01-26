<?php

namespace App\Services\Import\Responses;

use App\Enums\RequestEnum;
use App\Interfaces\ProviderInterface;
use App\Interfaces\ResponseInterface;
use App\Interfaces\SaverInterface;
use App\Interfaces\StorageInterface;
use App\Services\Import\NormalizeResponseService;
use App\Services\Import\SaverService;

class StreamResponse implements ResponseInterface
{
    private StorageInterface $storage;
    private NormalizeResponseService $normalizeResponseService;
    private SaverService $saverService;
    private $index = 0;

    public function __construct(
        StorageInterface $storage,
        NormalizeResponseService $normalizeResponseService,
        SaverService $saverService
    ) {
        $this->storage = $storage;
        $this->normalizeResponseService = $normalizeResponseService;
        $this->saverService = $saverService;
    }

    public function canImport(ProviderInterface $provider): bool
    {
        return $provider->getRequestType() === RequestEnum::STREAM;
    }

    public function import(ProviderInterface $provider)
    {
        $filePath = $provider->getResponse();
        $filePointer = $this->storage->pointerRead($filePath);
        $saver = $this->saverService->getSaver($provider);

        $this->save($provider, $filePointer, $saver);
        $this->clearCache($saver);
    }

    private function save(ProviderInterface $provider, $filePointer, SaverInterface $saver)
    {
        while (!$this->storage->isEndOfFile($filePointer)) {
            $lineFromFile = $this->storage->readOneLine($filePointer);
            $information = $this->normalizeResponseService->normalize($provider, $lineFromFile);

            $this->saveInformation($saver, $information);

            if ($this->isLimitReached($provider)) {
                break;
            }
        }
    }

    private function saveInformation($saver, $information)
    {
        if ($information === false) {
            return;
        }
        $saver->save($information);
        $this->index++;
    }

    private function clearCache(SaverInterface $saver)
    {
        $saver->clearCache();
    }

    private function isLimitReached(ProviderInterface $provider): bool
    {
        return $this->index == $provider::NUMBER_OF_MODELS_TO_IMPORT;
    }
}
