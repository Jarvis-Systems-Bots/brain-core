<?php

declare(strict_types=1);

namespace BrainCore\Enums;

enum McpTypeEnum: string
{
    case STDIO = 'stdio';
    case HTTP = 'http';
    case SSE = 'sse';

    public function isCommand(): bool
    {
        return in_array($this, [
            self::STDIO
        ]);
    }
}
