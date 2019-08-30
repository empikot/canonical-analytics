<?php

namespace App\IO\Console;

use App\IO\Console\Commands\Key\Generate as KeyGenerate;
use App\IO\Console\Commands\MongoInsertTest;
use App\IO\Console\Commands\RefreshLandingPageChecks;
use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        KeyGenerate::class,
        MongoInsertTest::class,
        RefreshLandingPageChecks::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command(RefreshLandingPageChecks::class)->cron('15 1 * * *');
    }
}
