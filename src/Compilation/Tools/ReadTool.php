<?php

declare(strict_types=1);

namespace BrainCore\Compilation\Tools;

use BrainCore\Abstracts\ToolAbstract;

class ReadTool extends ToolAbstract
{
    public static function name(): string
    {
        return 'Read';
    }
}
