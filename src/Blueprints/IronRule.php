<?php

declare(strict_types=1);

namespace BrainCore\Blueprints;

use Bfg\Dto\Collections\DtoCollection;
use Bfg\Dto\Dto;
use BrainCore\Blueprints\IronRule\OnViolation;
use BrainCore\Blueprints\IronRule\Text;
use BrainCore\Blueprints\IronRule\Why;
use BrainCore\Enums\IronRuleSeverityEnum;

class IronRule extends Dto
{
    /**
     * @param  string  $element
     * @param  string|null  $id
     * @param  \BrainCore\Enums\IronRuleSeverityEnum  $severity
     * @param  \Bfg\Dto\Collections\DtoCollection<int, Dto>  $child
     */
    public function __construct(
        protected string $element,
        protected string|null $id,
        protected IronRuleSeverityEnum $severity,
        protected DtoCollection $child,
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
     * Set ID
     *
     * @param  non-empty-string  $id
     * @return static
     */
    public function id(string $id): static
    {
        $this->id = $id;

        return $this;
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
     * @param  non-empty-string  $text
     * @return $this
     */
    public function text(string $text): static
    {
        $this->child->add(
            Text::fromAssoc(compact('text'))
        );

        return $this;
    }

    /**
     * Set Why
     *
     * @param  non-empty-string  $text
     * @return static
     */
    public function why(string $text): static
    {
        $this->child->add(
            Why::fromAssoc(compact('text'))
        );

        return $this;
    }

    /**
     * Set On Violation
     *
     * @param  non-empty-string  $text
     * @return static
     */
    public function onViolation(string $text): static
    {
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
