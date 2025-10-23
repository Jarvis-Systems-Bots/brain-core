<?php

declare(strict_types=1);

namespace BrainCore\Cortex;

use Bfg\Dto\Attributes\DtoItem;
use Bfg\Dto\Collections\DtoCollection;
use Bfg\Dto\Dto;
use BrainCore\Dto\IronRule;

class IronRules extends Dto
{
    /**
     * @param  \Bfg\Dto\Collections\DtoCollection  $rules
     */
    public function __construct(
        #[DtoItem(IronRule::class)]
        public DtoCollection $rules,
    ) {
    }

    /**
     * Add Rule
     *
     * @param  string  $text
     * @return \BrainCore\Dto\IronRule
     */
    public function rule(string $text): IronRule {

        $this->rules->add(
            $rule = IronRule::fromAssoc(
                compact('text')
            )
        );

        return $rule;
    }
}
