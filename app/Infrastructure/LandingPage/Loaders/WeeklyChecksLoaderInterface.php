<?php

namespace App\Infrastructure\LandingPage\Loaders;

use App\Domain\LandingPage\Aggregates\IndexabilityChecks;

interface WeeklyChecksLoaderInterface
{
    public function store(IndexabilityChecks $checks);
}
