<?php

namespace Tests\Infrastructure\DateTime\Transformers;

use App\Infrastructure\DateTime\Transformers\ToUtcDateTimeTransformer;
use TestCase;

class ToUtcDateTimeTransformerTest extends TestCase
{
    /**
     * @test
     */
    public function converting_to_utc_date_time()
    {
        $currentDt = new \DateTime((new \DateTime('now'))->format('Y-m-d H:i:s'));
        $this->assertEquals($currentDt, ToUtcDateTimeTransformer::transform($currentDt)->toDateTime());
    }
}
