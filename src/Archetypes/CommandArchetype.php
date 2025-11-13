<?php

declare(strict_types=1);

namespace BrainCore\Archetypes;

use BrainCore\Archetypes\Traits\MetasTrait;
use BrainCore\Archetypes\Traits\PurposeTrait;
use BrainCore\Archetypes\Traits\ResponseTrait;
use BrainCore\Archetypes\Traits\IronRulesTrait;
use BrainCore\Archetypes\Traits\GuidelinesTrait;
use BrainCore\Architectures\ArchetypeArchitecture;
use BrainCore\Archetypes\Traits\ExtractAttributesTrait;
use BrainCore\Attributes\Meta;
use Symfony\Component\VarExporter\VarExporter;

abstract class CommandArchetype extends ArchetypeArchitecture
{
    use MetasTrait;
    use PurposeTrait;
    use ResponseTrait;
    use IronRulesTrait;
    use GuidelinesTrait;
    use ExtractAttributesTrait;

    /**
     * Default element name.
     *
     * @return non-empty-string
     */
    protected static function defaultElement(): string
    {
        return 'command';
    }

    /**
     * Get command ID.
     *
     * @param  mixed  ...$args
     * @return string
     */
    public static function id(...$args): string
    {
        foreach ($args as $index => $arg) {
            try {
                $args[$index] = VarExporter::export($arg);
            } catch (\Throwable $e) {
                unset($args[$index]);
            }
        }
        $ref = new \ReflectionClass(static::class);
        $attributes = $ref->getAttributes(Meta::class);
        $id = 'unknown';
        foreach ($attributes as $attribute) {
            $meta = $attribute->newInstance();
            if ($meta->name === 'id') {
                $id = $meta->getText();
                break;
            }
        }
        return "/" . $id . (empty($args) ? '' : ' (' . implode(', ', $args) . ')');
    }
}
