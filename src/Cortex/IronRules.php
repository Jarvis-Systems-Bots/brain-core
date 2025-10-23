<?php

declare(strict_types=1);

namespace BrainCore\Cortex;

use Bfg\Dto\Collections\DtoCollection;
use BrainCore\Blueprints\IronRule;

class IronRules extends DtoCollection
{
    /**
     * Add Rule
     *
     * @param  string  $text
     * @return \BrainCore\Blueprints\IronRule
     */
    public function rule(string $text): IronRule {

        $this->add(
            $rule = IronRule::fromAssoc(
                compact('text')
            )
        );

        return $rule;
    }
}
