<?php

namespace App\Domain\LandingPage\Services;

use App\Infrastructure\LandingPage\Loaders\WeeklyChecksLoaderInterface;
use App\Infrastructure\LandingPage\Repositories\WeeklyChecksRepositoryInterface;

class WeeklyChecksStorage
{
    /**
     * @var bool
     */
    private $alreadyClearedCollection;

    /**
     * @var WeeklyChecksRepositoryInterface
     */
    private $checksRepository;

    /**
     * @var IndexabilityChecksCollector
     */
    private $checksCollector;

    /**
     * @var WeeklyChecksLoaderInterface
     */
    private $checksLoader;

    /**
     * WeeklyChecksStorage constructor.
     * @param WeeklyChecksRepositoryInterface $checksRepository
     * @param WeeklyChecksLoaderInterface $checksLoader
     * @param IndexabilityChecksCollector $checksCollector
     */
    public function __construct(
        WeeklyChecksRepositoryInterface $checksRepository,
        IndexabilityChecksCollector $checksCollector,
        WeeklyChecksLoaderInterface $checksLoader
    ) {
        $this->alreadyClearedCollection = false;
        $this->checksRepository = $checksRepository;
        $this->checksCollector = $checksCollector;
        $this->checksLoader = $checksLoader;
    }

    /**
     * @param string $url
     * @throws \Exception
     */
    public function storeChecksForSingleUrl(string $url)
    {
        $this->clearChecksCollection();
        $checks = $this->checksCollector->collect($url);
        $this->checksLoader->store($checks);
    }

    private function clearChecksCollection()
    {
        if (!$this->alreadyClearedCollection) {
            $this->checksRepository->removeAll();
            $this->alreadyClearedCollection = true;
        }
    }
}
