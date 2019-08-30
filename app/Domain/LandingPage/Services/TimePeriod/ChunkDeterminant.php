<?php

namespace App\Domain\LandingPage\Services\TimePeriod;

use App\Domain\LandingPage\ValueObjects\StatsFilterCriteria;

class ChunkDeterminant
{
    /**
     * @var array
     */
    private $checksFirstDates;

    /**
     * ChunkDeterminant constructor.
     * @param StatsFilterCriteria $filterCriteria
     * @param int $numberOfWeeks
     */
    public function __construct(StatsFilterCriteria $filterCriteria, int $numberOfWeeks)
    {
        $this->checksFirstDates = (new Splitter())->splitIntoWeeks(
            $filterCriteria,
            $numberOfWeeks
        );
    }

    /**
     * @param \DateTime $date
     * @return int
     */
    public function determineNumberOfWeek(\DateTime $date): int
    {
        $numberOfWeek = 0;
        foreach ($this->checksFirstDates as $numberOfCheck => $singleCheckFirstDate) {
            if ($date->getTimestamp() >= $singleCheckFirstDate->getTimestamp()) {
                $numberOfWeek = $numberOfCheck;
            }
        }
        return $numberOfWeek;
    }
}
