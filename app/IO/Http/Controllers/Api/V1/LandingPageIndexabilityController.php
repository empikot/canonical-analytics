<?php

namespace App\IO\Http\Controllers\Api\V1;

use App\Domain\LandingPage\Services\IndexabilityStatusChecker;
use App\IO\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LandingPageIndexabilityController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function show(Request $request): JsonResponse
    {
        return response()->json([
            'data' => app(IndexabilityStatusChecker::class)
                ->check($request->get('url'))->toArray()
        ], 200);
    }
}
