<?php

namespace App\Domain\LandingPage\Services;

use App\Domain\LandingPage\Aggregates\IndexabilityChecks;
use App\Domain\LandingPage\ValueObjects\IndexabilityStatus;

class IndexabilityStatusDeterminant
{
    const SECOND_TRESHOLD_MINIMAL_QUANTITY = 1,
        THIRD_TRESHOLD_MINIMAL_QUANTITY = 10;
    const FIRST_TRESHOLD_POINTS = 0,
        SECOND_TRESHOLD_POINTS = 1,
        THIRD_TRESHOLD_POINTS = 10;
    /**
     * @var IndexabilityStatusBuilder
     */
    private $indexabilityStatusBuilder;

    /**
     * IndexabilityStatusDeterminant constructor.
     * @param IndexabilityStatusBuilder $indexabilityStatusBuilder
     */
    public function __construct(IndexabilityStatusBuilder $indexabilityStatusBuilder)
    {
        $this->indexabilityStatusBuilder = $indexabilityStatusBuilder;
    }

    /**
     * @param IndexabilityChecks $indexabilityChecks
     * @return IndexabilityStatus
     */
    public function getStatus(IndexabilityChecks $indexabilityChecks): IndexabilityStatus
    {
        if ($this->checkIfThereAreNoNullableChecks($indexabilityChecks)) {
            return $this->indexabilityStatusBuilder->buildWithCanonicalWithoutChanges();
        }
        return $this->indexabilityStatusBuilder->build($this->calculatePointsForAllChecks($indexabilityChecks));
    }

    private function checkIfThereAreNoNullableChecks(IndexabilityChecks $indexabilityChecks): bool
    {
        foreach ($indexabilityChecks->getChecksQuantities() as $indexabilityCheck) {
            if ($indexabilityCheck === null) {
                return true;
            }
        }
        return false;
    }

    private function calculatePointsForAllChecks(IndexabilityChecks $indexabilityChecks): int
    {
        $points = 0;
        foreach ($indexabilityChecks->getChecksQuantities() as $quantity) {
            $points += $this->calculatePointsForSingleCheck($quantity);
        }
        return $points;
    }

    private function calculatePointsForSingleCheck(int $quantity): int
    {
        if ($quantity >= self::THIRD_TRESHOLD_MINIMAL_QUANTITY) {
            return self::THIRD_TRESHOLD_POINTS;
        } else if ($quantity >= self::SECOND_TRESHOLD_MINIMAL_QUANTITY) {
            return self::SECOND_TRESHOLD_POINTS;
        }
        return self::FIRST_TRESHOLD_POINTS;
    }
}
