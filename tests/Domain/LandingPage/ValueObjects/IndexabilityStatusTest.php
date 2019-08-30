<?php

namespace Tests\Domain\LandingPage\ValueObjects;

use App\Domain\LandingPage\ValueObjects\IndexabilityStatus;

class IndexabilityStatusTest extends \TestCase
{
    /**
     * @test
     */
    public function creating_indexability_status_value_object()
    {
        $canonicalStatus = 'aaa';
        $indexStatus = 'bbb';

        $status = new IndexabilityStatus($canonicalStatus, $indexStatus);

        $this->assertEquals($canonicalStatus, $status->getCanonicalStatus());
        $this->assertEquals($indexStatus, $status->getIndexStatus());
        $this->assertEquals([
            'canonical' => $canonicalStatus,
            'index' => $indexStatus
        ], $status->toArray());
    }
}
