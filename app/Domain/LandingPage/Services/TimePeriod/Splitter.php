<?php

namespace App\Domain\LandingPage\Services\TimePeriod;

use App\Domain\LandingPage\ValueObjects\StatsFilterCriteria;

class Splitter
{
    /**
     * @param StatsFilterCriteria $filterCriteria
     * @param int $numberOfWeeks
     * @return array
     */
    public function splitIntoWeeks(StatsFilterCriteria $filterCriteria, int $numberOfWeeks): array
    {
        $timePeriodsFirstDates = [];
        for ($i = 0; $i < $numberOfWeeks; $i++) {
            $timePeriodsFirstDates[$i + 1] = (clone $filterCriteria->getDateFrom())->modify("+$i weeks");
        }
        return $timePeriodsFirstDates;
    }
}
