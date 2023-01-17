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
    private $appliedStrategies = 0;

    public function import(HttpImportInterface $source)
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->canProcess($source)) {
                $this->markAppliedStrategy();
                try {
                    return $strategy->getResponse($source);
                } catch (Exception $e) {
                    $this->throwHttpInteractionException($e, $strategy, $source);
                }
            }
        }

        $this->checkNumberOfStrategiesApplied($source);
    }

    private function markAppliedStrategy()
    {
        $this->appliedStrategies++;
    }

    private function throwHttpInteractionException($e, $strategy, $source)
    {
        throw new HttpInteractionException(sprintf(
            'Strategy "%s" for source "%s" encountered an error: %s',
            get_class($strategy),
            $source,
            $e->getMessage()
        ));
    }

    private function checkNumberOfStrategiesApplied($source)
    {
        if ($this->appliedStrategies === 0) {
            throw new NoStrategyFoundException(sprintf(
                'No strategy found for source "%s".',
                $source->getHttpInteractionType()
            ));
        }
    }

    public function registerStrategy(HttpInteractionInterface $strategy)
    {
        $this->strategies[] = $strategy;
    }
}
