<?php

declare(strict_types=1);

namespace BrainCore\Architectures;

use Bfg\Dto\Dto;
use BrainCore\Architectures\Traits\ExtractMetaAttributesTrait;

abstract class McpArchitecture extends Dto
{
    use ExtractMetaAttributesTrait;

    /**
     * Track which classes have already registered their 'created' event listener.
     * This prevents registering the same listener multiple times.
     * @var array<string, bool>
     */
    private static array $eventListenersRegistered = [];

    public function __construct()
    {
        if (!isset(self::$eventListenersRegistered[static::class])) {
            static::on('created', function () {
                $this->extractMetaAttributes();
            });
            self::$eventListenersRegistered[static::class] = true;
        }
    }
}
