<?php

namespace App\Domain\LandingPage\Services;

use App\Domain\LandingPage\ValueObjects\IndexabilityStatus;

class IndexabilityStatusBuilder
{
    /**
     * @param int $points
     * @return IndexabilityStatus
     */
    public function build(int $points): IndexabilityStatus
    {
        if ($points === 40) {
            return $this->buildWithCanonicalOnSelf();
        } else if ($points >= 10) {
            return $this->buildWithCanonicalWithoutChanges();
        } else if ($points >= 1) {
            return $this->buildWithWiderCanonical();
        }
        return $this->buildWithoutCanonical();
    }

    /**
     * @return IndexabilityStatus
     */
    public function buildWithCanonicalOnSelf(): IndexabilityStatus
    {
        return new IndexabilityStatus(
            IndexabilityStatus::CANONICAL_STATUS_SELF,
            IndexabilityStatus::INDEX_STATUS_INDEX
        );
    }

    /**
     * @return IndexabilityStatus
     */
    public function buildWithCanonicalWithoutChanges(): IndexabilityStatus
    {
        return new IndexabilityStatus(
            IndexabilityStatus::CANONICAL_STATUS_NO_CHANGE,
            IndexabilityStatus::INDEX_STATUS_NO_CHANGE
        );
    }

    /**
     * @return IndexabilityStatus
     */
    public function buildWithWiderCanonical(): IndexabilityStatus
    {
        return new IndexabilityStatus(
            IndexabilityStatus::CANONICAL_STATUS_WIDER,
            IndexabilityStatus::INDEX_STATUS_NO_CHANGE
        );
    }

    /**
     * @return IndexabilityStatus
     */
    public function buildWithoutCanonical(): IndexabilityStatus
    {
        return new IndexabilityStatus(
            IndexabilityStatus::CANONICAL_STATUS_NONE,
            IndexabilityStatus::INDEX_STATUS_NO_INDEX
        );
    }
}
