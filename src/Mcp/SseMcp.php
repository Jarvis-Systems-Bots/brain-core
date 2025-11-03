<?php

declare(strict_types=1);

namespace BrainCore\Mcp;

use BrainCore\Architectures\McpArchitecture;
use BrainCore\Enums\McpTypeEnum;

abstract class SseMcp extends McpArchitecture
{
    /**
     * @param non-empty-string $url
     * @param array<string, string> $headers
     */
    public function __construct(
        protected McpTypeEnum $type,
        protected string $url,
        protected array $headers,
    ) {
        parent::__construct();
    }

    /**
     * @return McpTypeEnum
     */
    protected static function defaultType(): McpTypeEnum
    {
        return McpTypeEnum::SSE;
    }

    /**
     * @return non-empty-string
     */
    abstract protected static function defaultUrl(): string;

    /**
     * @return array<string, string>
     */
    abstract protected static function defaultHeaders(): array;
}
