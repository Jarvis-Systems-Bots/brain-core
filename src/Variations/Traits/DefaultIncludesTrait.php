<?php

declare(strict_types=1);

namespace BrainCore\Variations\Traits;

use BrainCore\Includes\Brain\BrainBasicErrorHandling;
use BrainCore\Includes\Brain\BrainCore;
use BrainCore\Includes\Brain\BrainCoreConstraints;
use BrainCore\Includes\Brain\BrainDelegationWorkflow;
use BrainCore\Includes\Brain\BrainResponseValidation;
use BrainCore\Includes\Brain\BrainScriptMasterDelegation;
use BrainCore\Includes\Brain\DelegationProtocols;
use BrainCore\Includes\Brain\PreActionValidation;
use BrainCore\Includes\Universal\BrainDocsCommand;
use BrainCore\Includes\Universal\BrainScriptsCommand;
use BrainCore\Includes\Universal\VectorMemoryMCP;

trait DefaultIncludesTrait
{
    /**
     * Handle the architecture logic.
     */
    protected function handle(): void
    {
        // === UNIVERSAL (Brain runtime essentials) ===
        $this->include(BrainCoreConstraints::class);            // Simplified constraints for Brain orchestration
        $this->include(VectorMemoryMCP::class);                 // Vector memory primary knowledge base
        $this->include(BrainDocsCommand::class);                // Documentation indexing and search command
        $this->include(BrainScriptsCommand::class);             // Brain scripts automation command
        $this->include(BrainScriptMasterDelegation::class);     // Master delegation patterns for Brain scripts

        // === BRAIN ORCHESTRATION (Brain-specific) ===
        $this->include(BrainCore::class);                       // Foundation + meta
        $this->include(PreActionValidation::class);             // Pre-action safety gate
        $this->include(DelegationProtocols::class);             // Delegation protocols
        $this->include(BrainDelegationWorkflow::class);         // Simplified delegation workflow
        $this->include(BrainResponseValidation::class);         // Agent response validation
        $this->include(BrainBasicErrorHandling::class);         // Basic error handling
    }
}
