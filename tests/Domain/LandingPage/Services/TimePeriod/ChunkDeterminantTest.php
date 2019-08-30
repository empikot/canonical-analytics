<?php

namespace Tests\Domain\LandingPage\Services\TimePeriod;

use App\Domain\LandingPage\Services\TimePeriod\ChunkDeterminant;
use App\Domain\LandingPage\ValueObjects\StatsFilterCriteria;

class ChunkDeterminantTest extends \TestCase
{
    /**
     * @test
     */
    public function determining_chunk_number()
    {
        $determinant = new ChunkDeterminant(
            new StatsFilterCriteria(
                new \DateTime('2019-04-01'),
                new \DateTime('2019-04-14')
            ),
            2
        );

        $this->assertEquals(1, $determinant->determineNumberOfWeek(new \DateTime('2019-04-01')));
        $this->assertEquals(1, $determinant->determineNumberOfWeek(new \DateTime('2019-04-03')));
        $this->assertEquals(1, $determinant->determineNumberOfWeek(new \DateTime('2019-04-07')));
        $this->assertEquals(2, $determinant->determineNumberOfWeek(new \DateTime('2019-04-08')));
        $this->assertEquals(2, $determinant->determineNumberOfWeek(new \DateTime('2019-04-11')));
        $this->assertEquals(2, $determinant->determineNumberOfWeek(new \DateTime('2019-04-14')));
        $this->assertEquals(2, $determinant->determineNumberOfWeek(new \DateTime('2019-04-15')));
    }
}
