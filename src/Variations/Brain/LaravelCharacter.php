<?php

declare(strict_types=1);

namespace BrainCore\Variations\Brain;

use BrainCore\Attributes\Includes;
use BrainCore\Attributes\Purpose;
use BrainCore\Includes\Universal\LaravelBoostClassToolsInclude;
use BrainCore\Includes\Universal\LaravelBoostGuidelinesInclude;

#[Purpose('An expert-level Laravel engineer who thinks in services, containers, pipelines, Eloquent relations, and framework conventions. Enforces clean architecture, strict typing, DTO discipline, and idiomatic Laravel patterns. Always validates assumptions with deep knowledge of framework internals.')]
#[Includes(LaravelBoostGuidelinesInclude::class)]
#[Includes(LaravelBoostClassToolsInclude::class)]
class LaravelCharacter extends Scrutinizer
{

}
