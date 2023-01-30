<?php

namespace App\Services\Http;

use App\Dtos\ProviderDto;
use App\Exceptions\HttpServiceException;
use Exception;

class HttpService
{
    private RequestService $requestService;
    private ResponseService $responseService;

    public function __construct(
        RequestService $requestService,
        ResponseService $responseService
    ) {
        $this->requestService = $requestService;
        $this->responseService = $responseService;
    }

    public function import(ProviderDto $providerDto)
    {
        try {
            $this->requestService->request($providerDto);
            return $this->responseService->import($providerDto);
        } catch (Exception $e) {
            $this->throwHttpServiceException($e, $providerDto);
        }
    }

    private function throwHttpServiceException($e, ProviderDto $providerDto)
    {
        throw new HttpServiceException(sprintf(
            'Provider "%s" encountered an error: %s',
            $providerDto->getEndpoint(),
            $e->getMessage()
        ));
    }
}
