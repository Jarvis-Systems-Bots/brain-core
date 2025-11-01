<?php

declare(strict_types=1);

namespace BrainCore\Archetypes\Traits;

use BrainCore\Blueprints\Response;

trait ResponseTrait
{
    /**
     * @return Response
     */
    public function response(): Response
    {
        return $this->findOrCreateOfChild(Response::class);
    }
}
