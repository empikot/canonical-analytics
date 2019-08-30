<?php
namespace App\Infrastructure\LandingPage\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Stat extends Eloquent
{
    protected $collection = 'landing_page_stats';

    protected $fillable = [
        'url',
        'url_canonical',
        'offers_quantity',
    ];

    protected $hidden = ['updated_at'];
}
