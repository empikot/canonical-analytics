<?php

namespace Tests\Domain\LandingPage\Services;

use App\Domain\LandingPage\Services\LandingPageStatsInMemoryStorage;
use App\Domain\LandingPage\Services\StatsFilterCriteriaBuilder;
use App\Infrastructure\LandingPage\Extractors\LandingPageStatsExtractor;

class LandingPageStatsInMemoryStorageTest extends \TestCase
{
    /**
     * @test
     */
    public function storing_no_stats()
    {
        $this->performSingleTestCase([], [], 'test');
    }

    /**
     * @test
     */
    public function storing_stats_for_only_one_landing_page()
    {
        $this->performSingleTestCase(
            [
                ['url' => 'test1', 'quantity' => 1],
                ['url' => 'test1', 'quantity' => 2],
                ['url' => 'test1', 'quantity' => 3],
            ],
            [
                ['url' => 'test1', 'quantity' => 1],
                ['url' => 'test1', 'quantity' => 2],
                ['url' => 'test1', 'quantity' => 3],
            ],
            'test1'
        );
    }

    /**
     * @test
     */
    public function storing_stats_for_many_landing_pages()
    {
        $this->performSingleTestCase(
            [
                ['url' => 'test1', 'quantity' => 1],
            ],
            [
                ['url' => 'test1', 'quantity' => 1],
                ['url' => 'test2', 'quantity' => 2],
                ['url' => 'test3', 'quantity' => 3],
            ],
            'test1'
        );

        $this->performSingleTestCase(
            [
                ['url' => 'test2', 'quantity' => 2],
            ],
            [
                ['url' => 'test1', 'quantity' => 1],
                ['url' => 'test2', 'quantity' => 2],
                ['url' => 'test3', 'quantity' => 3],
            ],
            'test2'
        );

        $this->performSingleTestCase(
            [
                ['url' => 'test3', 'quantity' => 3],
            ],
            [
                ['url' => 'test1', 'quantity' => 1],
                ['url' => 'test2', 'quantity' => 2],
                ['url' => 'test3', 'quantity' => 3],
            ],
            'test3'
        );
    }



    /**
     * @param array $expectedStats
     * @param array $actualStats
     * @param string $url
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    private function performSingleTestCase(array $expectedStats, array $actualStats, string $url)
    {
        $landingPageStatsExtractor = \Mockery::mock(LandingPageStatsExtractor::class)
            ->shouldReceive('getStats')
            ->withAnyArgs()
            ->andReturn($actualStats)
            ->getMock();

        $storage = new LandingPageStatsInMemoryStorage(
            $landingPageStatsExtractor,
            app(StatsFilterCriteriaBuilder::class)
        );
        $storage->init();
        $stats = $storage->getStats(app(StatsFilterCriteriaBuilder::class)->build($url));

        $this->assertEquals($expectedStats, $stats);
    }
}
