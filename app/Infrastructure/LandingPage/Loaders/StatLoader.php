<?php

namespace App\Infrastructure\LandingPage\Loaders;

use App\Domain\LandingPage\ValueObjects\StatValueObject;
use App\Infrastructure\LandingPage\Models\Stat;

class StatLoader
{
    public function store(StatValueObject $stat)
    {
        return Stat::create([
            'url' => $stat->getUrl(),
            'url_canonical' => $stat->getCanonical() ?? null,
            'offers_quantity' => $stat->getQuantity(),
        ]);
    }
}
