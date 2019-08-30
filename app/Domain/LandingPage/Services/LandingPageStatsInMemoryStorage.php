<?php

namespace App\Domain\LandingPage\Services;

use App\Domain\LandingPage\ValueObjects\StatsFilterCriteria;
use App\Infrastructure\LandingPage\Extractors\LandingPageStatsExtractor;
use App\Infrastructure\LandingPage\Extractors\LandingPageStatsExtractorInterface;

class LandingPageStatsInMemoryStorage implements LandingPageStatsExtractorInterface
{
    /**
     * @var LandingPageStatsExtractor
     */
    private $landingPageStatsExtractor;

    /**
     * @var StatsFilterCriteriaBuilder
     */
    private $filterCriteriaBuilder;

    /**
     * @var array
     */
    private $stats;

    /**
     * LandingPageStatsInMemoryStorage constructor.
     * @param LandingPageStatsExtractor $landingPageStatsExtractor
     * @param StatsFilterCriteriaBuilder $filterCriteriaBuilder
     */
    public function __construct(
        LandingPageStatsExtractor $landingPageStatsExtractor,
        StatsFilterCriteriaBuilder $filterCriteriaBuilder
    ) {
        $this->landingPageStatsExtractor = $landingPageStatsExtractor;
        $this->filterCriteriaBuilder = $filterCriteriaBuilder;
        $this->stats = [];
    }

    /**
     * pre-loads to memory all landing page stats
     */
    public function init()
    {
        $allStats = $this->landingPageStatsExtractor->getStats($this->filterCriteriaBuilder->build());
        foreach ($allStats as $stat) {
            if (!isset($this->stats[$stat['url']])) {
                $this->stats[$stat['url']] = [];
            }
            $this->stats[$stat['url']][] = $stat;
        }
    }

    /**
     * @param StatsFilterCriteria $filterCriteria
     * @return array
     */
    public function getStats(StatsFilterCriteria $filterCriteria): array
    {
        return $this->stats[$filterCriteria->getUrl()] ?? [];
    }
}
