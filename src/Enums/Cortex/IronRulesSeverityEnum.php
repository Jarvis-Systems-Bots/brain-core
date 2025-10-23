<?php

declare(strict_types=1);

namespace BrainCore\Enums\Cortex;

enum IronRulesSeverityEnum: string
{
    case UNSPECIFIED = 'unspecified';
    case CRITICAL = 'critical';
    case HIGH = 'high';
    case MEDIUM = 'medium';
    case LOW = 'low';
}
