<?php

declare(strict_types=1);

namespace BrainCore\Includes\Universal;

use BrainCore\Archetypes\BrainArchetype;
use BrainCore\Attributes\Purpose;

#[Purpose(<<<'PURPOSE'
Provides optional optimization and performance enhancement practices for Laravel-based systems operating under Brain orchestration layer.
Includes direct integration with MCP Laravel Boost toolchain for system inspection and optimization.
PURPOSE
)]
class LaravelBoostGuidelines extends BrainArchetype
{
    /**
     * Handle the architecture logic.
     */
    protected function handle(): void
    {
        $this->guideline('phase-performance-optimization')
            ->text('Goal: Improve application efficiency and response latency.')
            ->example('Utilize Redis for cache and Horizon for queue management.')
            ->example('Enable query caching and reduce N+1 queries via eager loading.')
            ->example('Leverage PHP OPcache and Laravel Octane when applicable.');

        $this->guideline('phase-code-architecture')
            ->text('Goal: Maintain strict, scalable, and testable Laravel architecture.')
            ->example('Adopt DTO pattern for request/response data structures.')
            ->example('Implement Service and Repository layers with strict typing.')
            ->example('Enforce PSR-12, PHPStan level 10, Rector and Pint validation.')
            ->example('Prefer dependency injection over facades for testability.');

        $this->guideline('phase-security-and-quality')
            ->text('Goal: Enhance robustness, validation, and compliance with Brain quality layers.')
            ->example('Integrate with quality gates for automated code scanning.')
            ->example('Follow core constraints for CPU and memory-safe task scheduling.')
            ->example('Use environment-specific encryption keys and sanitized configs.');

        $this->guideline('allowed-tools')
            ->example('mcp__laravel-boost__list-available-config-keys')
            ->example('mcp__laravel-boost__last-error')
            ->example('mcp__laravel-boost__list-artisan-commands')
            ->example('mcp__laravel-boost__database-schema')
            ->example('mcp__laravel-boost__report-feedback')
            ->example('mcp__laravel-boost__database-query')
            ->example('mcp__laravel-boost__get-config')
            ->example('mcp__laravel-boost__application-info')
            ->example('mcp__laravel-boost__search-docs')
            ->example('mcp__laravel-boost__list-available-env-vars')
            ->example('mcp__laravel-boost__get-absolute-url')
            ->example('mcp__laravel-boost__read-log-entries')
            ->example('mcp__laravel-boost__browser-logs')
            ->example('mcp__laravel-boost__tinker')
            ->example('mcp__laravel-boost__list-routes')
            ->example('mcp__laravel-boost__database-connections');

        $this->guideline('tools-policy')
            ->text('All listed tools are read-only or safe-execution operations; Brain authorization required for write-level actions.');

        $this->guideline('meta-controls-laravel')
            ->text('Optional module; CI does not require its presence but enforces checks if enabled.')
            ->example('All Laravel optimization and validation events logged to laravel_boost.log when active.')->key('logging')
            ->example('Architect Agent manages activation, tool access, and compliance thresholds.')->key('governance');
    }
}