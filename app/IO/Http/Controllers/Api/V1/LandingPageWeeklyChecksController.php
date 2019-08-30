<?php

namespace App\IO\Http\Controllers\Api\V1;

use App\Domain\LandingPage\Services\StatsFilterCriteriaBuilder;
use App\Infrastructure\LandingPage\Repositories\WeeklyChecksRepository;
use App\IO\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LandingPageWeeklyChecksController extends Controller
{
    public function show(Request $request)
    {
        $statsFilterCriteria = app(StatsFilterCriteriaBuilder::class)->build(base64_decode($request->url));

        return response()->json(['data' => [app(WeeklyChecksRepository::class)->findOneByUrl($statsFilterCriteria)]]);
    }
}

