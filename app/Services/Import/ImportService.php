<?php

namespace App\Services\Import;

use App\Exceptions\ImportException;
use App\Exceptions\NoStrategyFoundException;
use App\Interfaces\ImportInterface;
use Exception;

class ImportService
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
                    throw new ImportException(sprintf(
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

    public function registerStrategy(ImportInterface $strategy)
    {
        $this->strategies[] = $strategy;
    }
}
