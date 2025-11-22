<?php

declare(strict_types=1);

namespace BrainCore\Includes\Universal;

use BrainCore\Archetypes\IncludeArchetype;
use BrainCore\Attributes\Purpose;
use BrainCore\Compilation\Tools\BashTool;

#[Purpose(<<<'PURPOSE'
Defines brain script command protocol for project automation via standalone executable scripts.
Compact workflow integration patterns for repetitive task automation and custom tooling.
PURPOSE
)]
class BrainScriptsInclude extends IncludeArchetype
{
    protected function handle(): void
    {
        $this->guideline('brain-scripts-command')
            ->text('Standalone script system for project automation and repetitive task execution.')
            ->example('brain script - List all available scripts with descriptions')->key('list-all')
            ->example('brain make:script {name} - Create new script in .brain/scripts/{Name}Script.php')->key('create')
            ->example('brain script {name} - ONLY way to execute scripts')->key('execute')
            ->example('brain script {name} {args} --options - Execute with arguments and options')->key('execute-args')
            ->example('Scripts auto-discovered on execution, no manual registration needed')->key('auto-discovery')
            ->example('Scripts CANNOT be run directly via php command - only through brain script runner')->key('runner-only');

        $this->guideline('script-structure')
            ->text('Laravel Command-based structure with full console capabilities.')
            ->example('brain make:script {name} - generates complete template with all boilerplate')->key('template')
            ->example('Namespace: BrainScripts (required)')->key('namespace')
            ->example('Base: Illuminate\Console\Command')->key('base-class')
            ->example('Properties: $signature (command syntax), $description (help text)')->key('properties')
            ->example('Method: handle() - Execution logic')->key('method')
            ->example('Output: $this->info(), $this->line(), $this->error()')->key('output')
            ->example('Naming: kebab-case in CLI → PascalCase in PHP (test-example → TestExampleScript)')->key('naming');

        $this->guideline('script-context')
            ->text('Scripts execute in Brain ecosystem, isolated from project code.')
            ->example('Available: Laravel facades, Illuminate packages, HTTP client, filesystem, Process')->key('available')
            ->example('Project can be: PHP, Node.js, Python, Go, or any other language')->key('project-agnostic');

        $this->guideline('workflow-creation')
            ->goal('Create new automation script')
            ->example()
            ->phase('Identify repetitive task or automation need')
            ->phase(BashTool::describe('brain make:script {name}', 'Create script template'))
            ->phase('Edit .brain/scripts/{Name}Script.php')
            ->phase('Define $signature with arguments and options')
            ->phase('Implement handle() with task logic')
            ->phase('Add validation, error handling, output formatting')
            ->phase(BashTool::describe('brain script {name}', 'Test execution'));

        $this->guideline('workflow-execution')
            ->goal('Discover and execute existing scripts')
            ->example()
            ->phase(BashTool::describe('brain script', 'List available scripts'))
            ->phase('Review available scripts and descriptions')
            ->phase(BashTool::describe('brain script {name}', 'Execute script'))
            ->phase(BashTool::describe('brain script {name} {args} --options', 'Execute with parameters'))
            ->phase('Monitor output and handle errors');

        $this->guideline('integration-patterns')
            ->text('How scripts interact with project (via external interfaces only).')
            ->example('PHP projects: Process::run(["php", "artisan", "command"])')->key('php-artisan')
            ->example('Node.js projects: Process::run(["npm", "run", "script"])')->key('nodejs')
            ->example('Python projects: Process::run(["python", "script.py"])')->key('python')
            ->example('HTTP APIs: Http::get/post to project endpoints')->key('http')
            ->example('File operations: Storage, File facades for project files')->key('files')
            ->example('Database: Direct DB access if project uses same database')->key('database');

        $this->guideline('usage-patterns')
            ->text('When to use brain scripts.')
            ->example('Repetitive manual tasks - automate with script')->key('automation')
            ->example('Project-specific tooling - custom commands for team')->key('tooling')
            ->example('Data transformations - process files, migrate data')->key('data')
            ->example('External API integrations - fetch, sync, update')->key('api')
            ->example('Development workflows - setup, reset, seed, cleanup')->key('dev-workflow')
            ->example('Monitoring and reporting - health checks, stats, alerts')->key('monitoring')
            ->example('Code generation - scaffolding, boilerplate, templates')->key('generation');

        $this->guideline('best-practices')
            ->text('Script quality standards.')
            ->example('Validation: Validate all inputs before execution')->key('validation')
            ->example('Error handling: Catch exceptions, provide clear error messages')->key('error-handling')
            ->example('Output: Use $this->info/line/error for formatted output')->key('output')
            ->example('Progress: Show progress for long-running tasks')->key('progress')
            ->example('Dry-run: Provide --dry-run option for destructive operations')->key('dry-run')
            ->example('Confirmation: Confirm destructive actions with $this->confirm()')->key('confirmation')
            ->example('Documentation: Clear $description and argument descriptions')->key('documentation')
            ->example('Exit codes: Return appropriate exit codes (0 success, 1+ error)')->key('exit-codes');

        $this->rule('namespace-required')->critical()
            ->text('ALL scripts MUST use BrainScripts namespace. No exceptions.')
            ->why('Auto-discovery and execution require consistent namespace.')
            ->onViolation('Fix namespace to BrainScripts or script will not be discovered.');

        $this->rule('no-project-classes-assumption')->critical()
            ->text('NEVER assume project classes/code available in scripts. Scripts execute in Brain context only.')
            ->why('Scripts are Brain tools, completely isolated from project. Project can be any language (PHP/Node/Python/etc.).')
            ->onViolation('Use Process, Http, or file operations to interact with project via external interfaces.');

        $this->rule('descriptive-signatures')->high()
            ->text('Script $signature MUST include clear argument and option descriptions.')
            ->why('Self-documenting scripts improve usability and maintainability.')
            ->onViolation('Add descriptions to all arguments and options in $signature.');
    }
}
