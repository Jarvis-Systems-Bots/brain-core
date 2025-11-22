<?php

declare(strict_types=1);

namespace BrainCore\Includes\Brain;

use BrainCore\Archetypes\IncludeArchetype;
use BrainCore\Attributes\Purpose;

#[Purpose(<<<'PURPOSE'
Defines essential runtime constraints for Brain orchestration operations.
Simplified version focused on delegation-level limits without detailed CI/CD or agent-specific metrics.
PURPOSE
)]
class CoreConstraintsInclude extends IncludeArchetype
{
    /**
     * Handle the architecture logic.
     */
    protected function handle(): void
    {
        // === RUNTIME LIMITS ===

        $this->guideline('constraint-token-limit')
            ->text('Prevents excessive resource consumption and infinite response loops.')
            ->example('max-response-tokens = 1200')->key('limit')
            ->example('Abort task if estimated token count > 1200 before output stage')->key('action');

        $this->guideline('constraint-execution-time')
            ->text('Prevents long-running or hanging processes.')
            ->example('max-execution-seconds = 60')->key('limit')
            ->example('Terminate tasks exceeding runtime threshold')->key('action');

        $this->guideline('constraint-memory-usage')
            ->text('Ensures memory efficiency per operation.')
            ->example('max-memory = 512MB')->key('limit')
            ->example('Trigger compaction if memory usage > 80%')->key('action');

        $this->guideline('constraint-delegation-depth')
            ->text('Restricts delegation chain depth to prevent recursive loops.')
            ->example('max-depth = 2 (Brain → Architect → Specialist)')->key('limit')
            ->example('Block delegation exceeding depth limit')->key('action');

        // === MCP VECTOR MEMORY POLICY ===

        $this->rule('mcp-only-access')->critical()
            ->text('ALL memory operations MUST use MCP tools. NEVER access ./memory/ directory directly.')
            ->why('Vector memory exclusively managed by MCP server for data integrity and proper embedding generation.')
            ->onViolation('Block operation immediately. Use correct mcp__vector-memory__* tool instead.');

        $this->rule('prohibited-operations')->critical()
            ->text('FORBIDDEN operations: Read(./memory/*), Write(./memory/*), Bash("sqlite3 ./memory/*"), Bash("cat ./memory/*"), Bash("ls ./memory/"), any direct file system access to memory/ folder.')
            ->why('Direct access bypasses MCP server, corrupts embeddings, and breaks consistency.')
            ->onViolation('Block operation immediately. Use correct mcp__vector-memory__* tool instead.');
    }
}
