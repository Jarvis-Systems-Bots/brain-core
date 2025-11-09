<?php

declare(strict_types=1);

namespace BrainCore\Compilation\Tools;

use BrainCore\Architectures\ToolArchitecture;

class EditTool extends ToolArchitecture
{
    public static function name(): string
    {
        return 'Edit';
    }
}
