<?php

namespace Fnematov\LaravelSchedulerAttribute;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class ArtisanScheduler
{
    public function __construct(
        public string  $name,
        public ?string $cron = null,
        public ?string $schedule = null,
    )
    {
    }
}
