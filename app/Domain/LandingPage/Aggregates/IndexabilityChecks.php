<?php

namespace App\Domain\LandingPage\Aggregates;

use App\Domain\LandingPage\ValueObjects\OffersQuantityCheckCase;

class IndexabilityChecks
{
    /**
     * @var string
     */
    private $url;
    /**
     * @var array
     */
    private $checks;

    /**
     * IndexabilityChecks constructor.
     * @param string $url
     * @param int $numberOfChecks
     */
    public function __construct(string $url, int $numberOfChecks)
    {
        $this->url = $url;
        $this->checks = $this->getClearChecksMap($numberOfChecks);
    }

    /**
     * @param int $numberOfChecks
     * @return array
     */
    private function getClearChecksMap(int $numberOfChecks): array
    {
        $checks = [];
        for ($i = 1; $i <= $numberOfChecks; $i++) {
            $checks[$i] = null;
        }
        return $checks;
    }

    /**
     * @return array
     */
    public function getChecksQuantities(): array
    {
        $quantities = [];
        foreach ($this->checks as $key => $check) {
            $quantities[$key] = !$check ? $check : $check->getOffersQuantity();
        }
        return $quantities;
    }

    /**
     * @param int $checkNumber
     * @param OffersQuantityCheckCase $checkCase
     */
    public function appendSingleCheck(int $checkNumber, OffersQuantityCheckCase $checkCase)
    {
        if (array_key_exists($checkNumber, $this->checks)
            && ($this->checks[$checkNumber] === null
            || $this->checks[$checkNumber]->getDate()->getTimestamp() <= $checkCase->getDate()->getTimestamp())
        ) {
            $this->checks[$checkNumber] = $checkCase;
        }
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'url' => $this->url,
            'checks' => $this->getChecksQuantities()
        ];
    }
}
