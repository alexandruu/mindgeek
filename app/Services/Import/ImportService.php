<?php

namespace App\Services\Import;

use App\Enums\CategoryEnum;
use App\Exceptions\ImportServiceException;
use App\Exceptions\NoStrategyFoundException;
use App\Interfaces\ProviderInterface;
use Exception;

class ImportService
{
    private $providers = [];
    private $processedProviders = 0;
    private RequestService $requestService;
    private ResponseService $responseService;

    public function __construct(
        RequestService $requestService,
        ResponseService $responseService
    ) {
        $this->requestService = $requestService;
        $this->responseService = $responseService;
    }

    public function import(CategoryEnum $category)
    {
        foreach ($this->providers as $provider) {
            if ($provider->hasCategory($category)) {
                $this->incrementCounterForProcessedProviders();

                try {
                    $this->requestService->request($provider);
                    $this->responseService->import($provider);
                } catch (Exception $e) {
                    $this->throwImportServiceException($e, $provider, $category);
                }
            }
        }

        $this->checkHowManyStrategiesWereAppliedForCategory($category);
    }

    private function checkHowManyStrategiesWereAppliedForCategory($category)
    {
        if ($this->processedProviders === 0) {
            $this->throwNoStrategyFoundException($category);
        }
    }

    private function incrementCounterForProcessedProviders()
    {
        $this->processedProviders++;
    }

    private function throwImportServiceException($e, $provider, $category)
    {
        throw new ImportServiceException(sprintf(
            'Strategy "%s" for category "%s" encountered an error: %s',
            get_class($provider),
            $category->name,
            $e->getMessage()
        ));
    }

    private function throwNoStrategyFoundException($category)
    {
        throw new NoStrategyFoundException(sprintf(
            'No strategies found for category "%s".',
            $category->name
        ));
    }

    public function registerProvider(ProviderInterface $provider)
    {
        $this->providers[] = $provider;
    }
}
