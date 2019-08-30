<?php

namespace App\IO\Console\Commands;

use App\Domain\LandingPage\Services\IndexabilityChecksCollector;
use App\Domain\LandingPage\Services\LandingPageStatsInMemoryStorage;
use App\Domain\LandingPage\Services\StatsFilterCriteriaBuilder;
use App\Domain\LandingPage\Services\WeeklyChecksStorage;
use App\Infrastructure\LandingPage\Extractors\LandingPageStatsExtractorInterface;
use App\Infrastructure\LandingPage\Extractors\LandingPageUrlsExtractor;
use App\Infrastructure\LandingPage\Loaders\WeeklyChecksLoader;
use App\Infrastructure\LandingPage\Repositories\WeeklyChecksRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class RefreshLandingPageChecks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'landing-page-checks:refresh';

    /**
     * @throws \Exception
     */
    public function handle()
    {
        $this->line('Loading landing page urls to refresh...');
        $urls = $this->getUrlsToRefresh();
        $this->info('[OK] Loaded');
        $progressBar = $this->output->createProgressBar($urls->count());
        $this->line('Generating weekly checks for '.$urls->count().' urls...');

        // generating checks for each of loaded urls
        $weeklyChecksStorageService = new WeeklyChecksStorage(
            app(WeeklyChecksRepository::class),
            new IndexabilityChecksCollector(
                $this->getLandingPageStatsExtractor(),
                app(StatsFilterCriteriaBuilder::class)
            ),
            app(WeeklyChecksLoader::class)
        );
        $urls->each(function ($singleUrl) use ($weeklyChecksStorageService, $progressBar) {
            $weeklyChecksStorageService->storeChecksForSingleUrl($singleUrl);
            $progressBar->advance();
        });

        // finishing progress bar
        $progressBar->finish();
        // moving to new line
        $this->info('');
        $this->info('[OK] Done');
    }

    /**
     * @return Collection
     * @throws \Exception
     */
    private function getUrlsToRefresh(): Collection
    {
        $filterCriteria = (new StatsFilterCriteriaBuilder())->build('about:blank');
        return (new LandingPageUrlsExtractor())->findAllRecentOnes($filterCriteria);
    }

    /**
     * @return LandingPageStatsExtractorInterface
     */
    private function getLandingPageStatsExtractor(): LandingPageStatsExtractorInterface
    {
        $extractor = app(LandingPageStatsInMemoryStorage::class);
        $extractor->init();
        return $extractor;
    }
}
