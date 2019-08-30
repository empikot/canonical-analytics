<?php

namespace App\Infrastructure\LandingPage\Extractors;

use App\Domain\LandingPage\ValueObjects\StatsFilterCriteria;
use App\Infrastructure\DateTime\Transformers\ToUtcDateTimeTransformer;
use App\Infrastructure\LandingPage\Models\Stat;

class LandingPageStatsExtractor implements LandingPageStatsExtractorInterface
{
    public function getStats(StatsFilterCriteria $filterCriteria): array
    {
        return Stat::raw(function ($collection) use ($filterCriteria) {
            return $collection->aggregate(
                $this->getAggregateOperations($filterCriteria),
                ['allowDiskUse' => true]
            );
        })->toArray();
    }

    private function getAggregateOperations(StatsFilterCriteria $filterCriteria): array
    {
        return [
            $this->getFilterOperation($filterCriteria),
            $this->getSortOperation(),
            $this->getAddFieldOperation(),
            $this->getGroupByOperation(),
            $this->getFieldsToProject(),
        ];
    }

    private function getFilterOperation(StatsFilterCriteria $filterCriteria): array
    {
        return [
            '$match' => [
                'created_at' => [
                    '$lt' => ToUtcDateTimeTransformer::transform($filterCriteria->getDateTo()),
                    '$gte' => ToUtcDateTimeTransformer::transform($filterCriteria->getDateFrom())
                ],
                'url' => $filterCriteria->getUrl() ?? $this->getDefaultUrlFilter()
            ]
        ];
    }

    /**
     * @todo mpi: przeniesc regexa do configa?
     * @return array
     */
    private function getDefaultUrlFilter(): array
    {
        return [
            '$regex' => config('app.lamoda.url.regex'),
            '$options' => '$i'
        ];
    }

    private function getSortOperation(): array
    {
        return [
            '$sort' => [
                'created_at' => 1
            ]
        ];
    }

    private function getAddFieldOperation(): array
    {
        return [
            '$addFields' => [
                'creation_day' => [
                    '$dateFromParts' => [
                        'year' => ['$year' => '$created_at'],
                        'month' => ['$month' => '$created_at'],
                        'day' => ['$dayOfMonth' => '$created_at']
                    ]
                ]
            ]
        ];
    }

    private function getGroupByOperation(): array
    {
        return [
            '$group' => [
                '_id' => [
                    'creation_day' => '$creation_day',
                    'url' => '$url'
                ],
                'created_at' => ['$last' => '$created_at'],
                'url' => ['$first' => '$url'],
                'canonical' => ['$last' => '$url_canonical'],
                'quantity' => ['$last' => '$offers_quantity']
            ]
        ];
    }

    private function getFieldsToProject(): array
    {
        return [
            '$project' => [
                '_id' => 0,
                'created_at' => 1,
                'url' => 1,
                'canonical' => 1,
                'quantity' => 1
            ]
        ];
    }
}
