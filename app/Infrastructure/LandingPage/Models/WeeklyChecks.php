<?php

namespace App\Infrastructure\LandingPage\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class WeeklyChecks extends Eloquent
{
    protected $collection = 'landing_page_weekly_checks';

    protected $fillable = [
        'url',
        'checks'
    ];

    protected $hidden = ['updated_at', 'created_at'];
}
