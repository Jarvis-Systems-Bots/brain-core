<?php

declare(strict_types=1);

namespace BrainCore\Blueprints;

use Bfg\Dto\Dto;
use BrainCore\Enums\IronRuleSeverityEnum;

class IronRule extends Dto
{
    /**
     * @param  string|null  $id
     * @param  \BrainCore\Enums\IronRuleSeverityEnum  $severity
     * @param  string|null  $text
     * @param  string|null  $why
     * @param  string|null  $onViolation
     */
    public function __construct(
        public string|null $id = null,
        public IronRuleSeverityEnum $severity = IronRuleSeverityEnum::UNSPECIFIED,
        public string|null $text = null,
        public string|null $why = null,
        public string|null $onViolation = null,
    ) {
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
     * Set Why
     *
     * @param  non-empty-string  $why
     * @return static
     */
    public function why(string $why): static
    {
        $this->why = $why;

        return $this;
    }

    /**
     * Set On Violation
     *
     * @param  non-empty-string  $onViolation
     * @return static
     */
    public function onViolation(string $onViolation): static
    {
        $this->onViolation = $onViolation;

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
