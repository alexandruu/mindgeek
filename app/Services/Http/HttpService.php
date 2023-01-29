<?php

namespace App\Services\Http;

use App\Enums\CategoryEnum;
use App\Exceptions\HttpServiceException;
use App\Exceptions\NoStrategyFoundException;
use App\Interfaces\ProviderInterface;
use Exception;

class HttpService
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
                    $this->throwHttpServiceException($e, $provider, $category);
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

    private function throwHttpServiceException($e, $provider, $category)
    {
        throw new HttpServiceException(sprintf(
            'Provider "%s" for category "%s" encountered an error: %s',
            get_class($provider),
            $category->name,
            $e->getMessage()
        ));
    }

    private function throwNoStrategyFoundException($category)
    {
        throw new NoStrategyFoundException(sprintf(
            'No providers found for category "%s".',
            $category->name
        ));
    }

    public function registerProvider(ProviderInterface $provider)
    {
        $this->providers[] = $provider;
    }
}
