<?php

declare(strict_types=1);

namespace BrainCore\Includes\Brain;

use BrainCore\Archetypes\IncludeArchetype;
use BrainCore\Attributes\Purpose;
use BrainCore\Compilation\Tools\TaskTool;

#[Purpose(<<<'PURPOSE'
Defines Brain delegation patterns for ScriptMaster agent.
Brain-only knowledge about when and how to delegate script creation to ScriptMaster.
PURPOSE
)]
class BrainScriptMasterDelegation extends IncludeArchetype
{
    protected function handle(): void
    {
        $this->guideline('script-master-agent')
            ->text('ScriptMaster agent specializes in creating high-quality automation scripts.')
            ->example(TaskTool::agent('script-master', 'Create script for {task} with arguments {args} and options {opts}'))->key('delegation')
            ->example('ScriptMaster knows: Laravel Command API, best practices, error handling, validation')->key('expertise')
            ->example('Use for: Complex scripts, reusable automation, project-specific tooling')->key('use-cases')
            ->example('ScriptMaster ensures: Type safety, validation, documentation, testability')->key('quality');

        $this->guideline('script-delegation-triggers')
            ->text('When Brain should delegate script creation to ScriptMaster.')
            ->example('User requests automation for repetitive task')->key('automation-request')
            ->example('User needs custom tooling for project workflow')->key('tooling-request')
            ->example('Task requires Laravel Console features (prompts, validation, I/O)')->key('complex-script')
            ->example('Script needs robust error handling and validation')->key('quality-script')
            ->example('User wants to create script with specific arguments/options')->key('signature-script');

        $this->guideline('delegation-workflow')
            ->text('Brain workflow for delegating script creation.')
            ->example()
            ->phase('identify-task', 'User describes repetitive task or automation need')
            ->phase('assess-complexity', 'Determine if manual creation or ScriptMaster delegation needed')
            ->phase('delegate', 'IF complex OR quality-critical → Task(@agent-script-master, "Create {name} script for {purpose} with {requirements}")')
            ->phase('verify', 'Test script execution: brain script {name}')
            ->phase('iterate', 'IF issues → Re-delegate to ScriptMaster with feedback');

        $this->guideline('manual-vs-delegation')
            ->text('When Brain should create script manually vs delegate to ScriptMaster.')
            ->example('Manual: Simple, single-purpose scripts with basic output')->key('manual')
            ->example('Manual: Trivial wrappers around existing commands')->key('manual-wrapper')
            ->example('Delegate: Complex logic with validation and error handling')->key('delegate-complex')
            ->example('Delegate: Interactive prompts and user input')->key('delegate-interactive')
            ->example('Delegate: Database operations or external API calls')->key('delegate-integration')
            ->example('Delegate: Scripts requiring Laravel Console advanced features')->key('delegate-advanced');
    }
}