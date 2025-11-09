<?php

declare(strict_types=1);

namespace BrainCore\Compilation\Tools;

use BrainCore\Architectures\ToolArchitecture;

class WebSearchTool extends ToolArchitecture
{
    public static function name(): string
    {
        return 'WebSearch';
    }
}
