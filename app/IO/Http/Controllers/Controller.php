<?php

namespace App\IO\Http\Controllers;

use App\Domain\LandingPage\Aggregates\IndexabilityChecks;
use App\Domain\LandingPage\Services\IndexabilityStatusDeterminant;
use App\Domain\LandingPage\ValueObjects\IndexabilityStatus;
use App\Domain\LandingPage\ValueObjects\OffersQuantityCheckCase;
use App\Infrastructure\LandingPage\Extractors\LandingPageCanonicalUrlsExtractor;
use App\Infrastructure\LandingPage\Models\WeeklyChecks;
use App\IO\Services\CsvFileCreator;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    /**
     * Health check
     * @return string
     */
    public function index()
    {
        return 'OK';
    }

    /**
     * @param Request $request
     * @throws \Exception
     * @todo mpi: raport jest zapewne tymczasowy, kod do generowania raportu nie poddaje refactorowi
     */
    public function canonicalReport(Request $request)
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '1024M');
        /**
         * @todo mpi: zostawiam z premedytacja ta metode - bedzie mi potrzebna do zrobienia raportu csv
         */
        (new CsvFileCreator())->getFile(collect($this->getReportData()))->output('canonical-report.csv');
    }

    private function getReportData(): array
    {
        $reportData = [];
        $checks = $this->getChecks();

        foreach ($checks as $check) {
            $singleUrlEntry = [
                'url' => $check['url'],
                'canonical' => $check['canonical_url']
            ];
            $singleUrlEntry['is_indexable_old'] = ($singleUrlEntry['url'] === $singleUrlEntry['canonical']);
            if ($singleUrlEntry['is_indexable_old']) {
                $indexabilityChecks = new IndexabilityChecks('', 4);
                $weekIterator = 1;
                foreach ($check['checks'] as $oneWeekCheck) {
                    $singleUrlEntry['check'.$weekIterator] = $oneWeekCheck;
                    if ($oneWeekCheck !== null) {
                        $indexabilityChecks->appendSingleCheck($weekIterator,
                            (new OffersQuantityCheckCase(new \DateTime(), $oneWeekCheck)));
                    }
                    $weekIterator++;
                }
                $indexabilityStatus = app(IndexabilityStatusDeterminant::class)->getStatus($indexabilityChecks);
                switch ($indexabilityStatus->getIndexStatus()) {
                    case IndexabilityStatus::INDEX_STATUS_NO_INDEX:
                        $singleUrlEntry['robots_new'] = 'noindex';
                        break;
                    default:
                        $singleUrlEntry['robots_new'] = 'index';
                        break;
                }
                switch ($indexabilityStatus->getCanonicalStatus()) {
                    case IndexabilityStatus::CANONICAL_STATUS_NONE:
                        $singleUrlEntry['canonical_new'] = null;
                        break;
                    case IndexabilityStatus::CANONICAL_STATUS_WIDER:
                        $singleUrlEntry['canonical_new'] = $this->getWiderUrl($singleUrlEntry['canonical']);
                        break;
                    default:
                        $singleUrlEntry['canonical_new'] = $singleUrlEntry['canonical'];
                        break;
                }

            } else {
                $singleUrlEntry = array_merge($singleUrlEntry, [
                    'check1' => null,
                    'check2' => null,
                    'check3' => null,
                    'check4' => null,
                    'robots_new' => '-',
                    'canonical_new' => '-',
                ]);
            }
            $reportData[] = $singleUrlEntry;
        }

        return $reportData;
    }

    private function getChecks(): array
    {
        $canonicalMap = app(LandingPageCanonicalUrlsExtractor::class)->getCanonicalsMappedToUrl();
        $weeklyChecks = WeeklyChecks::all()->toArray();
        foreach ($weeklyChecks as &             $weeklyCheck) {
            $weeklyCheck['canonical_url'] = '';
            if (isset($canonicalMap[$weeklyCheck['url']])) {
                $weeklyCheck['canonical_url'] = $canonicalMap[$weeklyCheck['url']];
            }
        }
        return $weeklyChecks;
    }

    private function getWiderUrl(string $url): string
    {
        $fullUrlParts = explode('/katalog', $url);
        $domain = $fullUrlParts[0];
        $pathParts = explode('/', $fullUrlParts[1]);
        if (strpos($fullUrlParts[1], '/tag-') !== false) {
            return $domain . '/katalog/' . $pathParts[1];
        }
        if (count($pathParts) >= 2) {
            $newPathParts = ['/katalog'];
            foreach ($pathParts as $pathPart) {
                if (!$this->contains($pathPart, ['mat-', 'kolekcja-', '-m', '-kolor', 'size-', 'price-', 'dl-', 'dlr-', 'dln-', 'promocje'])) {
                    $newPathParts[] = $pathPart;
                }
            }
            return $domain . implode('/', $newPathParts);
        }
        return $domain . '/katalog';
    }

    private function contains(string $haystack, array $needles = []): bool
    {
        $contains = false;
        foreach ($needles as $needle) {
            if (strpos($haystack, $needle) !== false) {
                $contains = true;
            }
        }
        return $contains;
    }
}
