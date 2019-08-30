<?php

namespace App\IO\Console\Commands;

use App\Infrastructure\LandingPage\Models\Stat;
use App\Infrastructure\LandingPage\Models\WeeklyChecks;
use Illuminate\Console\Command;

class MongoInsertTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mongo:test';

    public function handle()
    {
//        WeeklyChecks::create([
//            'url' => 'http://google.pl/',
//            'checks' => [
//                1 => 123,
//                2 => 234,
//                3 => 345,
//                4 => 456,
//            ]
//        ]);
//        dd(WeeklyChecks::all());


        Stat::create([
            'url' => '/katalog/czerwony?plc=facebook',
            'url_canonical' => '/katalog/czerwony',
            'offers_quantity' => 15
        ]);

        dd(Stat::all()->count());
    }

}
