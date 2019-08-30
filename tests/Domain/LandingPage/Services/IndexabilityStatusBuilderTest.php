<?php

namespace Tests\Domain\LandingPage\Services;

use App\Domain\LandingPage\Services\IndexabilityStatusBuilder;
use App\Domain\LandingPage\ValueObjects\IndexabilityStatus;

class IndexabilityStatusBuilderTest extends \TestCase
{
    /**
     * @test
     */
    public function creating_status_with_canonical_on_self()
    {
        $this->performTestCase(
            40,
            IndexabilityStatus::CANONICAL_STATUS_SELF,
            IndexabilityStatus::INDEX_STATUS_INDEX
        );
    }

    /**
     * @test
     */
    public function creating_status_with_canonical_without_changes()
    {
        $this->performTestCase(
            10,
            IndexabilityStatus::CANONICAL_STATUS_NO_CHANGE,
            IndexabilityStatus::INDEX_STATUS_NO_CHANGE
        );

        $this->performTestCase(
            39,
            IndexabilityStatus::CANONICAL_STATUS_NO_CHANGE,
            IndexabilityStatus::INDEX_STATUS_NO_CHANGE
        );
    }

    /**
     * @test
     */
    public function creating_status_with_wider_canonical()
    {
        $this->performTestCase(
            1,
            IndexabilityStatus::CANONICAL_STATUS_WIDER,
            IndexabilityStatus::INDEX_STATUS_NO_CHANGE
        );

        $this->performTestCase(
            9,
            IndexabilityStatus::CANONICAL_STATUS_WIDER,
            IndexabilityStatus::INDEX_STATUS_NO_CHANGE
        );
    }

    /**
     * @test
     */
    public function creating_status_without_canonical()
    {
        $this->performTestCase(
            0,
            IndexabilityStatus::CANONICAL_STATUS_NONE,
            IndexabilityStatus::INDEX_STATUS_NO_INDEX
        );
    }

    private function performTestCase(int $points, string $expectedCanonicalStatus, string $expectedIndexStatus)
    {
        $status = (new IndexabilityStatusBuilder())->build($points);
        $this->assertEquals($expectedCanonicalStatus, $status->getCanonicalStatus());
        $this->assertEquals($expectedIndexStatus, $status->getIndexStatus());
    }
}
