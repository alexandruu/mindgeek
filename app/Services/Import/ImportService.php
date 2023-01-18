<?php

namespace App\Services\Import;

use App\Exceptions\ImportServiceException;
use App\Exceptions\NoStrategyFoundException;
use App\Interfaces\ImportInterface;
use Exception;

class ImportService
{
    private $strategies = [];

    public function import(string $source)
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->canImport($source)) {
                try {
                    return $strategy->import();
                } catch (Exception $e) {
                    $this->throwImportServiceException($e, $strategy, $source);
                }
            }
        }

        $this->throwNoStrategyFoundException($source);
    }

    private function throwImportServiceException($e, $strategy, $source)
    {
        throw new ImportServiceException(sprintf(
            'Strategy "%s" for source "%s" encountered an error: %s',
            get_class($strategy),
            $source,
            $e->getMessage()
        ));
    }

    private function throwNoStrategyFoundException($source)
    {
        throw new NoStrategyFoundException(sprintf(
            'No strategy found for source "%s".',
            $source
        ));
    }

    public function registerStrategy(ImportInterface $strategy)
    {
        $this->strategies[] = $strategy;
    }
}
