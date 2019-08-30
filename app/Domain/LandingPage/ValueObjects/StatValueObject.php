<?php

namespace App\Domain\LandingPage\ValueObjects;

class StatValueObject
{
    /**
     * @var string
     */
    private $url;
    /**
     * @var string
     */
    private $canonical;
    /**
     * @var int
     */
    private $quantity;

    /**
     * StatVO constructor.
     * @param string $url
     * @param string $canonical
     * @param int $quantity
     */
    public function __construct(string $url, string $canonical = '', int $quantity = 0)
    {
        $this->url = $url;
        $this->canonical = $canonical;
        $this->quantity = $quantity;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getCanonical(): string
    {
        return $this->canonical;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }
}
