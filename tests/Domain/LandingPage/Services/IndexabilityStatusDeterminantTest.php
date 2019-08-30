<?php

namespace Tests\Domain\LandingPage\Services;

use App\Domain\LandingPage\Aggregates\IndexabilityChecks;
use App\Domain\LandingPage\Services\IndexabilityStatusChecker;
use App\Domain\LandingPage\Services\IndexabilityStatusDeterminant;
use App\Domain\LandingPage\ValueObjects\IndexabilityStatus;
use App\Domain\LandingPage\ValueObjects\OffersQuantityCheckCase;

class IndexabilityStatusDeterminantTest extends \TestCase
{
    /**
     * @test
     */
    public function determinig_status_for_nullable_checks()
    {
        $this->performTestCase(
            [],
            IndexabilityStatus::CANONICAL_STATUS_NO_CHANGE,
            IndexabilityStatus::INDEX_STATUS_NO_CHANGE
        );

        $this->performTestCase(
            [1],
            IndexabilityStatus::CANONICAL_STATUS_NO_CHANGE,
            IndexabilityStatus::INDEX_STATUS_NO_CHANGE
        );

        $this->performTestCase(
            [1, 2],
            IndexabilityStatus::CANONICAL_STATUS_NO_CHANGE,
            IndexabilityStatus::INDEX_STATUS_NO_CHANGE
        );

        $this->performTestCase(
            [1, 2, 3],
            IndexabilityStatus::CANONICAL_STATUS_NO_CHANGE,
            IndexabilityStatus::INDEX_STATUS_NO_CHANGE
        );
    }

    /**
     * @test
     */
    public function determinig_status_for_not_null_checks()
    {
        $this->performTestCase(
            [0, 0, 0, 0],
            IndexabilityStatus::CANONICAL_STATUS_NONE,
            IndexabilityStatus::INDEX_STATUS_NO_INDEX
        );

        $this->performTestCase(
            [1, 0, 0, 0],
            IndexabilityStatus::CANONICAL_STATUS_WIDER,
            IndexabilityStatus::INDEX_STATUS_NO_CHANGE
        );

        $this->performTestCase(
            [1, 2, 3, 4],
            IndexabilityStatus::CANONICAL_STATUS_WIDER,
            IndexabilityStatus::INDEX_STATUS_NO_CHANGE
        );

        $this->performTestCase(
            [1, 2, 10, 4],
            IndexabilityStatus::CANONICAL_STATUS_NO_CHANGE,
            IndexabilityStatus::INDEX_STATUS_NO_CHANGE
        );

        $this->performTestCase(
            [0, 0, 10, 0],
            IndexabilityStatus::CANONICAL_STATUS_NO_CHANGE,
            IndexabilityStatus::INDEX_STATUS_NO_CHANGE
        );

        $this->performTestCase(
            [1, 0, 10, 0],
            IndexabilityStatus::CANONICAL_STATUS_NO_CHANGE,
            IndexabilityStatus::INDEX_STATUS_NO_CHANGE
        );

        $this->performTestCase(
            [10, 11, 12, 13],
            IndexabilityStatus::CANONICAL_STATUS_SELF,
            IndexabilityStatus::INDEX_STATUS_INDEX
        );
    }

    private function performTestCase(array $givenQuantities, string $expectedCanonicalStatus, string $expectedIndexStatus)
    {
        $indexabilityChecks = new IndexabilityChecks('', IndexabilityStatusChecker::NUMBER_OF_WEEKS_TO_CHECK);
        foreach ($givenQuantities as $i => $quantity) {
            $indexabilityChecks->appendSingleCheck($i + 1, new OffersQuantityCheckCase(new \DateTime(), $quantity));
        }

        $status = app(IndexabilityStatusDeterminant::class)->getStatus($indexabilityChecks);

        $this->assertEquals($expectedCanonicalStatus, $status->getCanonicalStatus());
        $this->assertEquals($expectedIndexStatus, $status->getIndexStatus());
    }
}
