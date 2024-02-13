<?php

namespace Fnematov\LaravelSchedulerAttribute;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class ArtisanScheduler
{
    public function __construct(
        public string $commandName,
        public ?string $repeater = null,
        public ?string $method = null,
    )
    {
    }
}
