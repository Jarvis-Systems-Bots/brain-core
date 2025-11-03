<?php

declare(strict_types=1);

namespace BrainCore\Architectures;

use Bfg\Dto\Dto;
use BrainCore\Architectures\Traits\ExtractMetaAttributesTrait;

abstract class McpArchitecture extends Dto
{
    use ExtractMetaAttributesTrait;

    public function __construct()
    {
        static::on('created', function () {
            $this->extractMetaAttributes();
        });
    }
}
