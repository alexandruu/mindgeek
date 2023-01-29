<?php

namespace App\Services\Http\Responses;

use App\Enums\RequestEnum;
use App\Interfaces\ProviderInterface;
use App\Interfaces\ResponseInterface;
use App\Interfaces\SaverInterface;
use App\Interfaces\StorageInterface;
use App\Services\Normalizers\NormalizeResponseService;
use App\Services\Savers\SaverService;

class StreamResponse implements ResponseInterface
{
    private StorageInterface $storageService;
    private NormalizeResponseService $normalizeResponseService;
    private SaverService $saverService;
    private $index = 0;

    public function __construct(
        StorageInterface $storageService,
        NormalizeResponseService $normalizeResponseService,
        SaverService $saverService
    ) {
        $this->storageService = $storageService;
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
        $filePointer = $this->storageService->pointerRead($filePath);
        $saver = $this->saverService->getSaver($provider);

        $this->save($provider, $filePointer, $saver);
        $this->clearCache($saver);
    }

    private function save(ProviderInterface $provider, $filePointer, SaverInterface $saver)
    {
        while (!$this->storageService->isEndOfFile($filePointer)) {
            $lineFromFile = $this->storageService->readOneLine($filePointer);
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
