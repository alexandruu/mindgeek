<?php

namespace App\Services\Import;

use App\Exceptions\ImportServiceException;
use App\Exceptions\NoStrategyFoundException;
use App\Interfaces\ImportInterface;
use Exception;

class ImportService
{
    private $strategies = [];

    public function import(string $sourceTag)
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->canImport($sourceTag)) {
                try {
                    return $strategy->import();
                } catch (Exception $e) {
                    $this->throwImportServiceException($e, $strategy, $sourceTag);
                }
            }
        }

        $this->throwNoStrategyFoundException($sourceTag);
    }

    private function throwImportServiceException($e, $strategy, $sourceTag)
    {
        throw new ImportServiceException(sprintf(
            'Strategy "%s" for source "%s" encountered an error: %s',
            get_class($strategy),
            $sourceTag,
            $e->getMessage()
        ));
    }

    private function throwNoStrategyFoundException($sourceTag)
    {
        throw new NoStrategyFoundException(sprintf(
            'No strategy found for source "%s".',
            $sourceTag
        ));
    }

    public function registerStrategy(ImportInterface $strategy)
    {
        $this->strategies[] = $strategy;
    }
}
