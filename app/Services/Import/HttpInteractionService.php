<?php

namespace App\Services\Import;

use App\Exceptions\HttpInteractionException;
use App\Exceptions\NoStrategyFoundException;
use App\Interfaces\HttpImportInterface;
use App\Interfaces\HttpInteractionInterface;
use Exception;

class HttpInteractionService
{
    private $strategies = [];

    public function import(HttpImportInterface $provider)
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->canProcess($provider)) {
                try {
                    return $strategy->process($provider);
                } catch (Exception $e) {
                    $this->throwHttpInteractionException($e, $strategy, $provider);
                }
            }
        }

        $this->throwNoStrategyFoundException($provider);
    }

    private function throwHttpInteractionException($e, $strategy, $provider)
    {
        throw new HttpInteractionException(sprintf(
            'Strategy "%s" for provider "%s" encountered an error: %s',
            get_class($strategy),
            get_class($provider),
            $e->getMessage()
        ));
    }

    private function throwNoStrategyFoundException($provider)
    {
        throw new NoStrategyFoundException(sprintf(
            'No strategy found for source "%s".',
            $provider->getHttpInteractionType()
        ));
    }

    public function registerStrategy(HttpInteractionInterface $strategy)
    {
        $this->strategies[] = $strategy;
    }
}
