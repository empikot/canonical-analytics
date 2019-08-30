<?php

namespace Tests\Domain\LandingPage\ValueObjects;

use App\Domain\LandingPage\ValueObjects\StatsFilterCriteria;

class StatFilterCriteriaTest extends \TestCase
{
    /**
     * @test
     */
    public function filter_criteria_without_url()
    {
        $dateFrom = "2019-04-01";
        $dateTo = "2019-04-28";

        $filterCriteria = new StatsFilterCriteria(
            new \DateTime($dateFrom),
            new \DateTime($dateTo)
        );

        $this->assertEquals($dateFrom, $filterCriteria->getDateFrom()->format('Y-m-d'));
        $this->assertEquals($dateTo, $filterCriteria->getDateTo()->format('Y-m-d'));
        $this->assertEquals(null, $filterCriteria->getUrl());
    }

    /**
     * @test
     */
    public function filter_criteria_with_url()
    {
        $dateFrom = "2019-04-01";
        $dateTo = "2019-04-28";
        $url = "https://google.pl/not-found";

        $filterCriteria = new StatsFilterCriteria(
            new \DateTime($dateFrom),
            new \DateTime($dateTo),
            $url
        );

        $this->assertEquals($dateFrom, $filterCriteria->getDateFrom()->format('Y-m-d'));
        $this->assertEquals($dateTo, $filterCriteria->getDateTo()->format('Y-m-d'));
        $this->assertEquals($url, $filterCriteria->getUrl());
    }
}
