<?php

declare(strict_types=1);

namespace BrainCore\Blueprints;

use BrainCore\Architectures\BlueprintArchitecture;
use BrainCore\Blueprints\IronRule\OnViolation;
use BrainCore\Blueprints\IronRule\Text;
use BrainCore\Blueprints\IronRule\Why;
use BrainCore\Enums\IronRuleSeverityEnum;

class IronRule extends BlueprintArchitecture
{
    /**
     * @param  non-empty-string|null  $id
     * @param  \BrainCore\Enums\IronRuleSeverityEnum  $severity
     */
    public function __construct(
        protected string|null $id,
        protected IronRuleSeverityEnum $severity,
    ) {
    }

    /**
     * Set default element
     *
     * @return non-empty-string
     */
    protected static function defaultElement(): string
    {
        return 'rule';
    }

    /**
     * Get default severity
     *
     * @return \BrainCore\Enums\IronRuleSeverityEnum
     */
    protected static function defaultSeverity(): IronRuleSeverityEnum
    {
        return IronRuleSeverityEnum::UNSPECIFIED;
    }

    /**
     * Set Severity
     *
     * @param  \BrainCore\Enums\IronRuleSeverityEnum|string  $severity
     * @return $this
     */
    public function severity(IronRuleSeverityEnum|string $severity): static
    {
        if (is_string($severity)) {

            $severity = IronRuleSeverityEnum::from($severity);
        }

        $this->severity = $severity;

        return $this;
    }

    /**
     * @param  list<string>|non-empty-string  $text
     * @return $this
     */
    public function text(array|string $text): static
    {
        if (is_array($text)) {
            $text = implode(" ", $text);
        }
        $this->child->add(
            Text::fromAssoc(compact('text'))
        );

        return $this;
    }

    /**
     * Set Why
     *
     * @param  list<string>|non-empty-string  $text
     * @return static
     */
    public function why(array|string $text): static
    {
        if (is_array($text)) {
            $text = implode(" ", $text);
        }
        $this->child->add(
            Why::fromAssoc(compact('text'))
        );

        return $this;
    }

    /**
     * Set On Violation
     *
     * @param  list<string>|non-empty-string  $text
     * @return static
     */
    public function onViolation(array|string $text): static
    {
        if (is_array($text)) {
            $text = implode(" ", $text);
        }
        $this->child->add(
            OnViolation::fromAssoc(compact('text'))
        );

        return $this;
    }

    /**
     * Set low severity
     *
     * @return static
     */
    public function low(): static
    {
        return $this->severity(IronRuleSeverityEnum::LOW);
    }

    /**
     * Set medium severity
     *
     * @return static
     */
    public function medium(): static
    {
        return $this->severity(IronRuleSeverityEnum::MEDIUM);
    }

    /**
     * Set high severity
     *
     * @return static
     */
    public function high(): static
    {
        return $this->severity(IronRuleSeverityEnum::HIGH);
    }

    /**
     * Set critical severity
     *
     * @return static
     */
    public function critical(): static
    {
        return $this->severity(IronRuleSeverityEnum::CRITICAL);
    }
}
