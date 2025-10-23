<?php

declare(strict_types=1);

namespace BrainCore\Dto;

use Bfg\Dto\Dto;
use BrainCore\Enums\Cortex\IronRulesSeverityEnum;

class IronRule extends Dto
{
    /**
     * @param  string|null  $id
     * @param  \BrainCore\Enums\Cortex\IronRulesSeverityEnum  $severity
     * @param  string|null  $text
     * @param  string|null  $why
     * @param  string|null  $onViolation
     */
    public function __construct(
        public string|null $id = null,
        public IronRulesSeverityEnum $severity = IronRulesSeverityEnum::UNSPECIFIED,
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
     * @param  \BrainCore\Enums\Cortex\IronRulesSeverityEnum  $severity
     * @return $this
     */
    public function severity(IronRulesSeverityEnum $severity): static
    {
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
}
