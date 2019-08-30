<?php

namespace App\IO\Http\Controllers\Api\V1;

use App\Domain\LandingPage\Services\StatsFilterCriteriaBuilder;
use App\Domain\LandingPage\ValueObjects\StatValueObject;
use App\Infrastructure\LandingPage\Extractors\RawStatsExtractor;
use App\Infrastructure\LandingPage\Loaders\StatLoader;
use App\IO\Http\Controllers\Controller;
use App\IO\Http\Requests\LandingPageStatCreation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LandingPageStatsController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function search(Request $request): JsonResponse
    {
        $statsFilterCriteria = (new StatsFilterCriteriaBuilder())
            ->setDateFrom($request->get('date_from'))
            ->setDateTo($request->get('date_to'))
            ->build($request->get('url'));
        return response()->json(
            ['data' => (new RawStatsExtractor())->getSumOfOffersQuantitiesByUrlAndCreationDateRange($statsFilterCriteria)],
            200
        );
    }

    /**
     * @param LandingPageStatCreation $request
     * @return JsonResponse
     */
    public function store(LandingPageStatCreation $request): JsonResponse
    {
        $landingPageStat = new StatValueObject(
            $request->input('url'),
            $request->input('url_canonical') ?? '',
            $request->input('offers_quantity')
        );
        return response()->json((new StatLoader())->store($landingPageStat), 201);
    }
}
