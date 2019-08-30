<?php

namespace App\Infrastructure\DateTime\Transformers;

use MongoDB\BSON\UTCDateTime;

class ToUtcDateTimeTransformer
{
    public static function transform(\DateTime $dateTime): UTCDateTime
    {
        return new UTCDateTime($dateTime->getTimestamp() * 1000);
    }
}
