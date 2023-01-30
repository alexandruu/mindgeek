<?php

namespace App\Services\Http\Responses;

use App\Dtos\ProviderDto;
use App\Enums\HttpTypeEnum;
use App\Interfaces\ResponseInterface;
use App\Interfaces\StorageInterface;
use App\Services\Normalizers\NormalizeResponseService;

class StreamResponse implements ResponseInterface
{
    private StorageInterface $storageService;
    private NormalizeResponseService $normalizeResponseService;
    private $index = 0;

    public function __construct(
        StorageInterface $storageService,
        NormalizeResponseService $normalizeResponseService
    ) {
        $this->storageService = $storageService;
        $this->normalizeResponseService = $normalizeResponseService;
    }

    public function canImport(ProviderDto $providerDto): bool
    {
        return $providerDto->getHttpType() === HttpTypeEnum::STREAM;
    }

    public function import(ProviderDto $providerDto)
    {
        $filePath = $providerDto->getResponseBody();
        $filePointer = $this->storageService->pointerRead($filePath);

        return $this->save($providerDto, $filePointer);
    }

    private function save(ProviderDto $providerDto, $filePointer)
    {
        while (!$this->storageService->isEndOfFile($filePointer)) {
            $lineFromFile = $this->storageService->readOneLine($filePointer);
            $information = $this->normalizeResponseService->normalize($providerDto, $lineFromFile);

            if ($information === false) {
                continue;
            }

            yield $information;

            $this->index++;

            if ($this->isLimitReached($providerDto)) {
                break;
            }
        }
    }

    private function isLimitReached(ProviderDto $providerDto): bool
    {
        return $this->index == $providerDto->getLimitOfImportedItems();
    }
}
