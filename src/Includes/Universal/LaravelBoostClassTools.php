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
class LaravelBoostClassTools extends IncludeArchetype
{
    protected function handle(): void
    {
        // TOOL OVERVIEW
        $this->guideline('tool-capabilities')
            ->text('Three complementary MCP tools for PHP class analysis (READ-ONLY, safe, 100x faster than Read)')
            ->example('class-list(path, has_trait?, has_interface?, has_method?, limit?, offset?) - Discovery')
            ->example('class-detail(class, visibility?, include_inherited?, methods_limit?) - Introspection')
            ->example('class-usages(target, usage_types?, exclude_vendor?, limit?) - Impact analysis');

        // USAGE STRATEGIES
        $this->guideline('strategy-discovery')
            ->text('class-list: Fast discovery by trait/interface/method filters')
            ->example('Repositories: class-list(path: "app", has_interface: "RepositoryInterface")')
            ->example('Soft deletes: class-list(path: "app/Models", has_trait: "SoftDeletes")')
            ->example('Event listeners: class-list(path: "app/Listeners", has_method: "handle")');

        $this->guideline('strategy-introspection')
            ->text('class-detail: Deep inspection with visibility/inheritance controls')
            ->example('Service API: class-detail(class: "App\\Services\\ReviewsService", visibility: "public")')
            ->example('Repository contract: class-detail(class: "App\\Repositories\\ReviewsRepo", include_inherited: false)')
            ->example('Model structure: class-detail(class: "App\\Models\\Review", properties: true, constants: true)');

        $this->guideline('strategy-impact-analysis')
            ->text('class-usages: Find all references before refactoring')
            ->example('All usages: class-usages(target: "App\\Dto\\CreateReviewDto")')
            ->example('Interface implementations: class-usages(target: "App\\Contracts\\ReviewsInterface", usage_types: ["implements"])')
            ->example('Static calls: class-usages(target: "App\\Helpers\\PhoneHelper", usage_types: ["static_call"], exclude_vendor: true)');

        // WORKFLOWS
        $this->guideline('common-workflows')
            ->text('Sequential patterns for comprehensive analysis')
            ->example('Full analysis: class-list → forEach: class-detail → class-usages → complete ecosystem map')
            ->example('Pre-refactoring: class-detail(include_inherited: true) → class-usages(group_by_type: true) → impact estimation')
            ->example('Architecture validation: class-list(filter) → forEach: class-detail → verify compliance');

        // FILTERS
        $this->guideline('filtering-options')
            ->text('Precise filtering via trait/interface/method/visibility (full namespace required)')
            ->example('has_trait: "SoftDeletes" | has_interface: "*RepositoryInterface" | has_method: "handle"')
            ->example('visibility: "public" (API) | "protected" (internal) | "all" (complete analysis)');

        // PAGINATION
        $this->guideline('pagination-strategy')
            ->text('Use limit/offset when: classes > 50, methods > 20, usages > 100. Progressive disclosure.')
            ->example('class-list(..., limit: 20, offset: 0) → Page 1, offset: 20 → Page 2')
            ->example('class-detail(..., methods_limit: 10, methods_offset: 0) → First 10 methods');

        // INTEGRATION
        $this->guideline('tool-integration')
            ->text('Complement other tools: Explore (discovery) → class tools (refinement), vector memory (insights storage), docs (API generation)')
            ->example('Explore unknown → class-list refine → class-detail analyze')
            ->example('After analysis: mcp__vector-memory__store_memory(category: "architecture", tags: ["php"])');

        // PERFORMANCE
        $this->guideline('performance-benefits')
            ->text('100x faster than Read (metadata index vs file I/O). Auto-cached, auto-invalidated. Use Read only for implementation details.')
            ->example('class-list: ~10ms/100 classes | Read: ~1000ms/100 files');

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
            ->text('class-detail and class-usages require FULL namespace, not file path')
            ->why('Tools use reflection/AST, not filesystem. "App\\Models\\User" required, "app/Models/User.php" fails.')
            ->onViolation('Convert file path to namespace: app/Models/User.php → App\\Models\\User');

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

        $this->rule('architecture-validation-mandatory')
            ->severity('high')
            ->text('MUST use class tools for architecture compliance validation (readonly DTOs, interface contracts, trait usage)')
            ->why('Manual verification error-prone. Automated validation ensures consistency across codebase.')
            ->onViolation('Implement automated validation: class-list → class-detail → verify compliance');
    }
}
