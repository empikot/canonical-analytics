<?php

namespace Tests\Domain\LandingPage\Aggregates;

use App\Domain\LandingPage\Aggregates\IndexabilityChecks;
use App\Domain\LandingPage\ValueObjects\OffersQuantityCheckCase;

class IndexabilityChecksTest extends \TestCase
{
    /**
     * @test
     */
    public function creating_new_aggregate_without_checks()
    {
        $checks = new IndexabilityChecks('', 0);
        $this->assertEquals([], $checks->getChecksQuantities());
    }

    /**
     * @test
     */
    public function appending_single_check_aggregate_without_checks()
    {
        $checks = new IndexabilityChecks('', 0);
        $checks->appendSingleCheck(1, new OffersQuantityCheckCase(new \DateTime(), 1));
        $this->assertEquals([], $checks->getChecksQuantities());
    }

    /**
     * @test
     */
    public function creating_new_aggregate_with_checks()
    {
        $checks = new IndexabilityChecks('', 1);
        $this->assertEquals([1 => null], $checks->getChecksQuantities());

        $checks = new IndexabilityChecks('', 2);
        $this->assertEquals([1 => null, 2 => null], $checks->getChecksQuantities());
    }

    /**
     * @test
     */
    public function appending_single_check_to_aggregate_with_one_check()
    {
        $checks = new IndexabilityChecks('', 1);

        $checks->appendSingleCheck(1, new OffersQuantityCheckCase(new \DateTime('2019-04-01'), 1));
        $this->assertEquals([1 => 1], $checks->getChecksQuantities());

        $checks->appendSingleCheck(2, new OffersQuantityCheckCase(new \DateTime('2019-04-02'), 2));
        $this->assertEquals([1 => 1], $checks->getChecksQuantities());

        $checks->appendSingleCheck(1, new OffersQuantityCheckCase(new \DateTime('2019-04-03'), 3));
        $this->assertEquals([1 => 3], $checks->getChecksQuantities());

        $checks->appendSingleCheck(1, new OffersQuantityCheckCase(new \DateTime('2019-04-01'), 41));
        $this->assertEquals([1 => 3], $checks->getChecksQuantities());
    }

    /**
     * @test
     */
    public function appending_single_check_to_aggregate_with_number_of_checks_more_than_one()
    {
        $checks = new IndexabilityChecks('', 2);

        $checks->appendSingleCheck(1, new OffersQuantityCheckCase(new \DateTime('2019-04-01'), 1));
        $this->assertEquals([1 => 1, 2 => null], $checks->getChecksQuantities());

        $checks->appendSingleCheck(2, new OffersQuantityCheckCase(new \DateTime('2019-04-02'), 2));
        $this->assertEquals([1 => 1, 2 => 2], $checks->getChecksQuantities());

        $checks->appendSingleCheck(1, new OffersQuantityCheckCase(new \DateTime('2019-04-03'), 3));
        $this->assertEquals([1 => 3, 2 => 2], $checks->getChecksQuantities());

        $checks->appendSingleCheck(2, new OffersQuantityCheckCase(new \DateTime('2019-04-01'), 42));
        $this->assertEquals([1 => 3, 2 => 2], $checks->getChecksQuantities());
    }
}
