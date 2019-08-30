<?php

namespace Tests\Domain\LandingPage\ValueObjects;

use App\Domain\LandingPage\ValueObjects\StatValueObject;
use TestCase;

class StatValueObjectTest extends TestCase
{
    /**
     * @test
     */
    public function creating_new_stat_value_object()
    {
        $url = 'example1';
        $canonical = 'example2';
        $quantity = 1;

        $stat = new StatValueObject($url, $canonical, $quantity);

        $this->assertEquals($url, $stat->getUrl());
        $this->assertEquals($canonical, $stat->getCanonical());
        $this->assertEquals($quantity, $stat->getQuantity());
    }
}
