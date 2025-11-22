<?php

declare(strict_types=1);

namespace BrainCore\Includes\Universal;

use BrainCore\Archetypes\IncludeArchetype;
use BrainCore\Attributes\Purpose;

#[Purpose(<<<'PURPOSE'
Laravel Boost MCP class indexing and introspection tools for PHP codebase analysis.
Provides fast, metadata-based class discovery without reading files directly.
Essential for architecture validation, impact analysis, and code navigation.
PURPOSE
)]
class LaravelBoostClassToolsInclude extends IncludeArchetype
{
    protected function handle(): void
    {
        // TOOL SIGNATURES
        $this->guideline('tool-class-list')
            ->text('class-list: Discovery by path with optional filters. Returns class metadata without file I/O.')
            ->example('class-list(path, has_trait?, has_interface?, has_method?, recursive?, limit?, offset?)');

        $this->guideline('tool-class-detail')
            ->text('class-detail: Deep introspection of single class. Accepts FULL namespace OR file path.')
            ->example('class-detail(class, visibility?, include_inherited?, properties?, constants?, methods?, methods_limit?, methods_offset?, summary?, summary_mode?, static_only?)');

        $this->guideline('tool-class-usages')
            ->text('class-usages: Find all references to class. Requires FULL namespace.')
            ->example('class-usages(target, path?, usage_types?, exclude_vendor?, group_by_type?, sort_by?, limit?, offset?)');

        // USAGE TYPES
        $this->guideline('usage-types-reference')
            ->text('Available usage_types for class-usages filter')
            ->example('import - Use statements (imports)')
            ->example('new - Object instantiation')
            ->example('static_call - Static method calls (Class::method)')
            ->example('extends - Class inheritance')
            ->example('implements - Interface implementation')
            ->example('trait - Trait usage')
            ->example('type_hint - Parameter/return type hints');

        // USAGE STRATEGIES
        $this->guideline('strategy-discovery')
            ->text('class-list: Fast discovery by trait/interface/method filters. Full namespace for filters.')
            ->example('Repositories: class-list(path: "app", has_interface: "App\\Contracts\\RepositoryInterface")')
            ->example('Soft deletes: class-list(path: "app/Models", has_trait: "Illuminate\\Database\\Eloquent\\SoftDeletes")')
            ->example('Event listeners: class-list(path: "app/Listeners", has_method: "handle")');

        $this->guideline('strategy-introspection')
            ->text('class-detail: Deep inspection with visibility/inheritance controls')
            ->example('Service API: class-detail(class: "App\\Services\\ReviewsService", visibility: "public")')
            ->example('Repository contract: class-detail(class: "App\\Repositories\\ReviewsRepo", include_inherited: false)')
            ->example('Model structure: class-detail(class: "App\\Models\\Review", properties: true, constants: true)');

        $this->guideline('strategy-impact-analysis')
            ->text('class-usages: Find all references before refactoring')
            ->example('All usages: class-usages(target: "App\\Dto\\CreateReviewDto")')
            ->example('Implementations only: class-usages(target: "App\\Contracts\\ReviewsInterface", usage_types: ["implements"])')
            ->example('Grouped by type: class-usages(target: "App\\Helpers\\PhoneHelper", group_by_type: true)');

        // WORKFLOW
        $this->guideline('workflow-analysis')
            ->text('Refactoring workflow: discover → inspect → impact → count affected')
            ->example('class-list(path) → class-detail(class, include_inherited: true) → class-usages(target, group_by_type: true)');

        // PAGINATION
        $this->guideline('pagination-strategy')
            ->text('Use limit/offset for large results: classes > 50, methods > 20, usages > 100')
            ->example('class-list(..., limit: 20, offset: 0) → Page 1; offset: 20 → Page 2')
            ->example('class-detail(..., methods_limit: 10, methods_offset: 0) → First 10 methods');

        // ==========================================
        // IRON RULES
        // ==========================================

        $this->rule('prefer-class-tools-over-read')
            ->severity('high')
            ->text('MUST use class tools for PHP metadata discovery instead of Read when possible')
            ->why('Class tools: 100x faster, provide structured metadata, no parsing needed. Read: Only for implementation details.')
            ->onViolation('Replace Read with appropriate class tool: class-list for discovery, class-detail for introspection, class-usages for impact');

        $this->rule('validate-before-refactoring')
            ->severity('critical')
            ->text('MUST run class-usages before any class rename, deletion, or interface change')
            ->why('Breaking changes without impact analysis cause cascading failures. class-usages identifies ALL affected code.')
            ->onViolation('STOP. Run class-usages first, analyze impact, plan migration strategy, then proceed');

        $this->rule('namespace-required')
            ->severity('critical')
            ->text('Filter parameters (has_trait, has_interface, target) require FULL namespace. class-detail accepts namespace OR file path.')
            ->why('Filters use reflection/AST matching. Short names fail silently or match wrong classes.')
            ->onViolation('Use full namespace: "Illuminate\\Database\\Eloquent\\SoftDeletes" not "SoftDeletes"');

        $this->rule('pagination-for-scale')
            ->severity('medium')
            ->text('SHOULD use pagination (limit/offset) when class count > 50 or method count > 20')
            ->why('Token limits and response size. Progressive disclosure prevents overwhelming output.')
            ->onViolation('Add limit parameter: class-list(..., limit: 20, offset: 0) for large result sets');

        $this->rule('filter-specificity')
            ->severity('medium')
            ->text('SHOULD use most specific filter available (trait > interface > method > none)')
            ->why('Specific filters reduce result set, improve performance, provide better precision.')
            ->onViolation('Add has_trait/has_interface/has_method filter instead of scanning all classes');
    }
}
