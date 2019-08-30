<?php

namespace App\Domain\LandingPage\ValueObjects;

class OffersQuantityCheckCase
{
    /**
     * @var \DateTime
     */
    private $date;
    /**
     * @var int
     */
    private $offersQuantity;

    /**
     * OffersQuantityCheckCase constructor.
     * @param \DateTime $date
     * @param int $offersQuantity
     */
    public function __construct(\DateTime $date, int $offersQuantity)
    {
        $this->date = $date;
        $this->offersQuantity = $offersQuantity;
    }

    /**
     * @return \DateTime
     */
    public function getDate(): \DateTime
    {
        return $this->date;
    }

    /**
     * @return int
     */
    public function getOffersQuantity(): int
    {
        return $this->offersQuantity;
    }
}
