<?php

namespace App\Services;

use App\Exceptions\ActorsImportException;
use App\Exceptions\NoStrategyFoundException;
use App\Interfaces\ActorsApi;
use Exception;

class ActorsImport
{
    private $strategies = [];

    public function import(string $source)
    {
        $appliedStrategies = 0;
        foreach ($this->strategies as $strategy) {
            if ($strategy->canImport($source)) {
                $appliedStrategies++;
                try {
                    $strategy->import();
                } catch (Exception $e) {
                    throw new ActorsImportException(sprintf(
                        'Strategy "%s" for source "%s" encountered an error: %s',
                        get_class($strategy),
                        $source,
                        $e->getMessage()
                    ));
                }
            }
        }

        if ($appliedStrategies === 0) {
            throw new NoStrategyFoundException(sprintf(
                'No strategy found for source "%s".',
                $source
            ));
        }
    }

    public function registerStrategy(ActorsApi $strategy)
    {
        $this->strategies[] = $strategy;
    }
}
