<?php

namespace App\Domain\LandingPage\Services;

use App\Domain\LandingPage\Aggregates\IndexabilityChecks;
use App\Domain\LandingPage\Services\TimePeriod\ChunkDeterminant;
use App\Domain\LandingPage\ValueObjects\IndexabilityStatus;
use App\Domain\LandingPage\ValueObjects\OffersQuantityCheckCase;
use App\Domain\LandingPage\ValueObjects\StatsFilterCriteria;
use App\Infrastructure\LandingPage\Extractors\LandingPageStatsExtractor;

class IndexabilityStatusChecker
{
    const NUMBER_OF_WEEKS_TO_CHECK = 4;
    /**
     * @var LandingPageStatsExtractor
     */
    private $landingPageStatsExtractor;
    /**
     * @var IndexabilityStatusDeterminant
     */
    private $indexabilityDeterminant;
    /**
     * @var StatsFilterCriteriaBuilder
     */
    private $statsFilterCriteriaBuilder;

    /**
     * IndexabilityStatusChecker constructor.
     * @param LandingPageStatsExtractor $landingPageStatsExtractor
     * @param IndexabilityStatusDeterminant $indexabilityDeterminant
     * @param StatsFilterCriteriaBuilder $statsFilterCriteriaBuilder
     */
    public function __construct(
        LandingPageStatsExtractor $landingPageStatsExtractor,
        IndexabilityStatusDeterminant $indexabilityDeterminant,
        StatsFilterCriteriaBuilder $statsFilterCriteriaBuilder
    ) {
        $this->landingPageStatsExtractor = $landingPageStatsExtractor;
        $this->indexabilityDeterminant = $indexabilityDeterminant;
        $this->statsFilterCriteriaBuilder = $statsFilterCriteriaBuilder;
    }

    /**
     * @param string $url
     * @return IndexabilityStatus
     * @throws \Exception
     */
    public function check(string $url): IndexabilityStatus
    {
        $filterCriteria = $this->getLandingPageStatsFilterCriteria($url);
        $chunkDeterminant = $this->getChunkDeterminant($filterCriteria);
        $landingPageStats = $this->landingPageStatsExtractor->getStats($filterCriteria);

        $indexabilityChecks = new IndexabilityChecks($url, self::NUMBER_OF_WEEKS_TO_CHECK);
        foreach ($landingPageStats as $singleStat) {
            $statCreationDate = new \DateTime($singleStat['created_at']);
            $indexabilityChecks->appendSingleCheck(
                $chunkDeterminant->determineNumberOfWeek($statCreationDate),
                new OffersQuantityCheckCase($statCreationDate, $singleStat['quantity'])
            );
        }

        return $this->indexabilityDeterminant->getStatus($indexabilityChecks);
    }

    private function getLandingPageStatsFilterCriteria(string $url): StatsFilterCriteria
    {
        return $this->statsFilterCriteriaBuilder->build($url);
    }

    private function getChunkDeterminant(StatsFilterCriteria $filterCriteria): ChunkDeterminant
    {
        return new ChunkDeterminant($filterCriteria, self::NUMBER_OF_WEEKS_TO_CHECK);
    }
}
