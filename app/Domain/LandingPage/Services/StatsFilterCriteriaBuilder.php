<?php


namespace App\Domain\LandingPage\Services;


use App\Domain\LandingPage\ValueObjects\StatsFilterCriteria;

class StatsFilterCriteriaBuilder
{
    /**
     * @var \DateTime
     */
    private $dateFrom;
    /**
     * @var \DateTime
     */
    private $dateTo;

    /**
     * StatsFilterCriteriaBuilder constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->dateTo = new \DateTime('+1 day midnight');
        $this->dateFrom = (clone $this->dateTo)
            ->modify('-'.IndexabilityStatusChecker::NUMBER_OF_WEEKS_TO_CHECK.' weeks midnight');
    }

    /**
     * @param string|null $url
     * @return StatsFilterCriteria
     */
    public function build(string $url = null): StatsFilterCriteria
    {
        return new StatsFilterCriteria(
            $this->dateFrom,
            $this->dateTo,
            $url
        );
    }

    /**
     * @param string|null $dateFrom
     * @return StatsFilterCriteriaBuilder
     * @throws \Exception
     */
    public function setDateFrom(string $dateFrom = null): self
    {
        if ($dateFrom) {
            $this->dateFrom = new \DateTime($dateFrom);
        }
        return $this;
    }

    /**
     * @param string|null $dateTo
     * @return StatsFilterCriteriaBuilder
     * @throws \Exception
     */
    public function setDateTo(string $dateTo = null): self
    {
        if ($dateTo) {
            $this->dateTo = new \DateTime($dateTo);
        }
        return $this;
    }
}
