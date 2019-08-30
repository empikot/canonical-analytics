<?php

namespace Tests\Domain\LandingPage\ValueObjects;

use App\Domain\LandingPage\ValueObjects\OffersQuantityCheckCase;

class OffersQuantityCheckCaseTest extends \TestCase
{
    /**
     * @test
     */
    public function creating_offers_quantity_check_case_value_object()
    {
        $date = "2019-04-03";
        $quantity = 123;

        $checkCase = new OffersQuantityCheckCase(new \DateTime($date), $quantity);

        $this->assertEquals($date, $checkCase->getDate()->format('Y-m-d'));
        $this->assertEquals($quantity, $checkCase->getOffersQuantity());
    }
}
