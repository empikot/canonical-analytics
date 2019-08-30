<?php

namespace App\Domain\LandingPage\ValueObjects;

class StatsFilterCriteria
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
     * @var string|null
     */
    private $url;

    /**
     * StatsFilterCriteria constructor.
     * @param \DateTime $dateFrom
     * @param \DateTime $dateTo
     * @param string|null $url
     */
    public function __construct(\DateTime $dateFrom, \DateTime $dateTo, string $url = null)
    {
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
        $this->url = $url;
    }

    /**
     * @return \DateTime
     */
    public function getDateFrom(): \DateTime
    {
        return $this->dateFrom;
    }

    /**
     * @return \DateTime
     */
    public function getDateTo(): \DateTime
    {
        return $this->dateTo;
    }

    /**
     * @return string|null
     */
    public function getUrl() //: ?string
    {
        return $this->url;
    }
}
