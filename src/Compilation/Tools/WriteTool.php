<?php

declare(strict_types=1);

namespace BrainCore\Compilation\Tools;

use BrainCore\Abstracts\ToolAbstract;

class WriteTool extends ToolAbstract
{
    public static function name(): string
    {
        return 'Write';
    }
}
