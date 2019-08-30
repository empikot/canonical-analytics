<?php


namespace App\Infrastructure\LandingPage\Repositories;


use App\Domain\LandingPage\ValueObjects\StatsFilterCriteria;
use App\Infrastructure\LandingPage\Models\WeeklyChecks;

class WeeklyChecksRepository implements WeeklyChecksRepositoryInterface
{
    /**
     * @param StatsFilterCriteria $filterCriteria
     * @return WeeklyChecks|null
     */
    public function findOneByUrl(StatsFilterCriteria $filterCriteria)
    {
        return WeeklyChecks::where('url', $filterCriteria->getUrl())->first();
    }

    /**
     * removes all weekly checks from collection
     */
    public function removeAll()
    {
        WeeklyChecks::where('created_at', '<>', null)->delete();
    }
}
