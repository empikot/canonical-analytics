<?php

namespace Tests\Domain\LandingPage\Services;

use App\Domain\LandingPage\Services\IndexabilityChecksCollector;
use App\Domain\LandingPage\Services\StatsFilterCriteriaBuilder;
use App\Infrastructure\LandingPage\Extractors\LandingPageStatsExtractor;

class IndexabilityChecksCollectorTest extends \TestCase
{
    /**
     * @test
     */
    public function landing_page_with_no_stats()
    {
        $this->performSingleTestCase(
            [
                1 => null,
                2 => null,
                3 => null,
                4 => null
            ],
            []
        );
    }

    /**
     * @test
     */
    public function landing_page_with_single_stat()
    {
        $this->performSingleTestCase(
            [
                1 => null,
                2 => null,
                3 => null,
                4 => 0
            ],
            [
                ['created_at' => (new \DateTime('-3 days'))->format('Y-m-d H:i:s'), 'quantity' => 0]
            ]
        );
    }

    /**
     * @test
     */
    public function landing_page_with_two_stats()
    {
        $this->performSingleTestCase(
            [
                1 => null,
                2 => null,
                3 => 123,
                4 => 0
            ],
            [
                ['created_at' => (new \DateTime('-3 days'))->format('Y-m-d H:i:s'), 'quantity' => 0],
                ['created_at' => (new \DateTime('-10 days'))->format('Y-m-d H:i:s'), 'quantity' => 123]
            ]
        );
    }

    /**
     * @test
     */
    public function landing_page_with_all_stats()
    {
        $this->performSingleTestCase(
            [
                1 => 1,
                2 => 2,
                3 => 3,
                4 => 4
            ],
            [
                ['created_at' => (new \DateTime('-3 days'))->format('Y-m-d H:i:s'), 'quantity' => 4],
                ['created_at' => (new \DateTime('-10 days'))->format('Y-m-d H:i:s'), 'quantity' => 3],
                ['created_at' => (new \DateTime('-17 days'))->format('Y-m-d H:i:s'), 'quantity' => 2],
                ['created_at' => (new \DateTime('-24 days'))->format('Y-m-d H:i:s'), 'quantity' => 1],
            ]
        );
    }

    /**
     * @param array $expectedResult
     * @param array $actualLandingPageStats
     * @throws \Exception
     */
    private function performSingleTestCase(array $expectedResult, array $actualLandingPageStats)
    {
        $landingPageStatsExtractor = \Mockery::mock(LandingPageStatsExtractor::class)
            ->shouldReceive('getStats')
            ->withAnyArgs()
            ->andReturn($actualLandingPageStats)
            ->getMock();
        $collector = new IndexabilityChecksCollector(
            $landingPageStatsExtractor,
            app(StatsFilterCriteriaBuilder::class)
        );

        $checks = $collector->collect('test-url');

        $this->assertEquals($expectedResult, $checks->toArray()['checks']);
    }
}
