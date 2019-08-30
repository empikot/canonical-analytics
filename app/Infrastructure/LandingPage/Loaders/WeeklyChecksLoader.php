<?php

namespace App\Infrastructure\LandingPage\Loaders;

use App\Domain\LandingPage\Aggregates\IndexabilityChecks;
use App\Infrastructure\LandingPage\Models\WeeklyChecks;

class WeeklyChecksLoader implements WeeklyChecksLoaderInterface
{
    public function store(IndexabilityChecks $checks)
    {
        WeeklyChecks::create($checks->toArray());
    }
}
