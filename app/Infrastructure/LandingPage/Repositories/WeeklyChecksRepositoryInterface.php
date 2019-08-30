<?php

namespace App\Infrastructure\LandingPage\Repositories;

use App\Domain\LandingPage\ValueObjects\StatsFilterCriteria;

interface WeeklyChecksRepositoryInterface
{
    public function findOneByUrl(StatsFilterCriteria $filterCriteria);
    public function removeAll();
}
