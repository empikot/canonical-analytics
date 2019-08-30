<?php

namespace App\Infrastructure\LandingPage\Extractors;

use App\Infrastructure\LandingPage\Models\Stat;

class LandingPageCanonicalUrlsExtractor
{
    /**
     * @return array
     */
    public function getCanonicalsMappedToUrl(): array
    {
        $urlAndCanonicalsList = Stat::raw(function ($collection) {
            return $collection->aggregate(
                $this->getAggregateOperations(),
                ['allowDiskUse' => true]
            );
        })->toArray();

        $map = [];
        foreach ($urlAndCanonicalsList as $item) {
            $map[$item['url']] = $item['url_canonical'];
        }
        return $map;
    }

    private function getAggregateOperations(): array
    {
        return [
            [
                '$match' => [
                    'url' => [
                        '$regex' => config('app.lamoda.url.regex'),
                        '$options' => '$i'
                    ]
                ]
            ],
            [
                '$group' => [
                    '_id' => '$url',
                    'url' => ['$last' => '$url'],
                    'url_canonical' => ['$last' => '$url_canonical']
                ]
            ],
            [
                '$project' => [
                    '_id' => 0,
                    'url' => 1,
                    'url_canonical' => 1
                ]
            ]
        ];
    }
}
