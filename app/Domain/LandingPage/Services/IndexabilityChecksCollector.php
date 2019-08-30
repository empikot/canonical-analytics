<?php

namespace App\Domain\LandingPage\Services;

use App\Domain\LandingPage\Aggregates\IndexabilityChecks;
use App\Domain\LandingPage\Services\TimePeriod\ChunkDeterminant;
use App\Domain\LandingPage\ValueObjects\OffersQuantityCheckCase;
use App\Infrastructure\LandingPage\Extractors\LandingPageStatsExtractorInterface;

class IndexabilityChecksCollector
{
    const NUMBER_OF_WEEKS_TO_CHECK = 4;
    /**
     * @var LandingPageStatsExtractorInterface
     */
    private $landingPageStatsExtractor;
    /**
     * @var ChunkDeterminant
     */
    private $weekDeterminant;
    /**
     * @var StatsFilterCriteriaBuilder
     */
    private $statsFilterCriteriaBuilder;

    /**
     * IndexabilityChecksCollector constructor.
     * @param LandingPageStatsExtractorInterface $landingPageStatsExtractor
     * @param StatsFilterCriteriaBuilder $statsFilterCriteriaBuilder
     */
    public function __construct(
        LandingPageStatsExtractorInterface $landingPageStatsExtractor,
        StatsFilterCriteriaBuilder $statsFilterCriteriaBuilder
    ) {
        $this->landingPageStatsExtractor = $landingPageStatsExtractor;
        $this->statsFilterCriteriaBuilder = $statsFilterCriteriaBuilder;
        $this->weekDeterminant = new ChunkDeterminant(
            $this->statsFilterCriteriaBuilder->build(),
            self::NUMBER_OF_WEEKS_TO_CHECK
        );
    }

    /**
     * @param string $url
     * @return IndexabilityChecks
     * @throws \Exception
     */
    public function collect(string $url): IndexabilityChecks
    {
        $filterCriteria = $this->statsFilterCriteriaBuilder->build($url);

        return $this->aggregateQuantitiesToIndexabilityChecks(
            new IndexabilityChecks($filterCriteria->getUrl(), self::NUMBER_OF_WEEKS_TO_CHECK),
            $this->landingPageStatsExtractor->getStats($filterCriteria)
        );
    }

    /**
     * @param IndexabilityChecks $indexabilityChecks
     * @param array $landingPageStats
     * @return IndexabilityChecks
     * @throws \Exception
     */
    private function aggregateQuantitiesToIndexabilityChecks(
        IndexabilityChecks $indexabilityChecks,
        array $landingPageStats
    ): IndexabilityChecks {
        foreach ($landingPageStats as $singleStat) {
            $statCreationDate = new \DateTime($singleStat['created_at']);
            $indexabilityChecks->appendSingleCheck(
                $this->weekDeterminant->determineNumberOfWeek($statCreationDate),
                new OffersQuantityCheckCase($statCreationDate, $singleStat['quantity'])
            );
        }
        return $indexabilityChecks;
    }
}
