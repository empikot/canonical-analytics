<?php

namespace App\Domain\LandingPage\ValueObjects;

class IndexabilityStatus
{
    const CANONICAL_STATUS_NO_CHANGE = 'NO_CHANGE',
        CANONICAL_STATUS_SELF = 'SELF',
        CANONICAL_STATUS_WIDER = 'WIDER',
        CANONICAL_STATUS_NONE = 'NONE',
        INDEX_STATUS_NO_CHANGE = 'NO_CHANGE',
        INDEX_STATUS_NO_INDEX = 'NO_INDEX',
        INDEX_STATUS_INDEX = 'INDEX';

    /**
     * @var string
     */
    private $canonicalStatus;
    /**
     * @var string
     */
    private $indexStatus;

    /**
     * IndexabilityStatus constructor.
     * @param string $canonicalStatus
     * @param string $indexStatus
     */
    public function __construct(string $canonicalStatus, string $indexStatus)
    {
        $this->canonicalStatus = $canonicalStatus;
        $this->indexStatus = $indexStatus;
    }

    /**
     * @return string
     */
    public function getCanonicalStatus(): string
    {
        return $this->canonicalStatus;
    }

    /**
     * @return string
     */
    public function getIndexStatus(): string
    {
        return $this->indexStatus;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'canonical' => $this->canonicalStatus,
            'index' => $this->indexStatus
        ];
    }
}
