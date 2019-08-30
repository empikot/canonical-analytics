<?php

namespace Tests\Domain\LandingPage\Services;

use App\Domain\LandingPage\Services\StatsFilterCriteriaBuilder;

class StatsFilterCriteriaBuilderTest extends \TestCase
{
    /**
     * @test
     */
    public function building_default_stats_filter_criteria_value_object()
    {
        $builder = new StatsFilterCriteriaBuilder();

        $filterCriteria = $builder->build();

        $this->assertEquals(null, $filterCriteria->getUrl());
        $this->assertEquals(true, $filterCriteria->getDateFrom() !== $filterCriteria->getDateTo());
    }

    /**
     * @test
     */
    public function building_default_stats_filter_criteria_value_object_with_url()
    {
        $url = 'https://lamoda.pl/katalog/ubrania';
        $builder = new StatsFilterCriteriaBuilder();

        $filterCriteria = $builder->build($url);

        $this->assertEquals($url, $filterCriteria->getUrl());
        $this->assertEquals(true, $filterCriteria->getDateFrom() !== $filterCriteria->getDateTo());
    }

    /**
     * @test
     */
    public function building_stats_filter_criteria_value_object_with_custom_dates()
    {
        $dateFrom = '2019-04-01';
        $dateTo = '2019-04-28';
        $builder = new StatsFilterCriteriaBuilder();

        $filterCriteria = $builder
            ->setDateFrom($dateFrom)
            ->setDateTo($dateTo)
            ->build();

        $this->assertEquals(null, $filterCriteria->getUrl());
        $this->assertEquals($dateFrom, $filterCriteria->getDateFrom()->format('Y-m-d'));
        $this->assertEquals($dateTo, $filterCriteria->getDateTo()->format('Y-m-d'));
    }

    /**
     * @test
     */
    public function building_stats_filter_criteria_value_object_with_custom_dates_and_url()
    {
        $dateFrom = '2019-04-01';
        $dateTo = '2019-04-28';
        $url = 'https://lamoda.pl/katalog/buty';
        $builder = new StatsFilterCriteriaBuilder();

        $filterCriteria = $builder
            ->setDateFrom($dateFrom)
            ->setDateTo($dateTo)
            ->build($url);

        $this->assertEquals($url, $filterCriteria->getUrl());
        $this->assertEquals($dateFrom, $filterCriteria->getDateFrom()->format('Y-m-d'));
        $this->assertEquals($dateTo, $filterCriteria->getDateTo()->format('Y-m-d'));
    }
}
