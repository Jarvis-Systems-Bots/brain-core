<?php

declare(strict_types=1);

namespace BrainCore\Includes\Agent;

use BrainCore\Archetypes\IncludeArchetype;
use BrainCore\Attributes\Purpose;

#[Purpose(<<<'PURPOSE'
Defines GitHub Issue hierarchy interpretation model for PM and execution-aware agents.
Ensures consistent understanding of task levels, dependencies, and milestones across all project agents.
Provides structured framework for parsing and validating issue relationships.
PURPOSE
)]
class GithubHierarchy extends IncludeArchetype
{
    /**
     * Handle the architecture logic.
     */
    protected function handle(): void
    {
        $this->guideline('level-epic')
            ->text('Epic: high-level feature or deliverable grouping multiple tasks.')
            ->example('type:epic')->key('label')
            ->example('none (top-level)')->key('relation')
            ->example()
                ->phase('logic-1', 'Represents high-level feature or deliverable grouping multiple tasks')
                ->phase('logic-2', 'Epics define strategic goals or functional areas')
                ->phase('validation-1', 'Must have at least one child issue labeled type:task')
                ->phase('validation-2', 'Should include milestone or release version reference');

        $this->guideline('level-task')
            ->text('Task: single unit of implementation work.')
            ->example('type:task')->key('label')
            ->example('child of epic')->key('relation')
            ->example()
                ->phase('logic-1', 'Represents single unit of implementation work')
                ->phase('logic-2', 'May contain subtasks for granular tracking')
                ->phase('validation-1', 'Must reference parent epic issue via linked field')
                ->phase('validation-2', 'Assigned developer must be defined')
                ->phase('validation-3', 'Each task must include labels for priority (P1–P3)');

        $this->guideline('level-subtask')
            ->text('Subtask: individual development, QA, or integration effort.')
            ->example('type:subtask')->key('label')
            ->example('child of task')->key('relation')
            ->example()
                ->phase('logic-1', 'Used to track individual development, QA, or integration efforts')
                ->phase('logic-2', 'All subtasks inherit labels and milestone from parent task')
                ->phase('validation-1', 'Must link to parent task')
                ->phase('validation-2', 'Must include status (todo, in-progress, done)');

        $this->guideline('metadata-milestone')
            ->text('Milestone metadata requirements.')
            ->example('release/version')->key('field')
            ->example('All epics and tasks must include milestone reference')->key('requirement');

        $this->guideline('metadata-labels')
            ->text('Required and optional label schemas.')
            ->example('priority:P1|P2|P3')->key('required-1')
            ->example('status:todo|in-progress|done')->key('required-2')
            ->example('component:*')->key('optional-1')
            ->example('team:*')->key('optional-2');

        $this->guideline('parsing-logic')
            ->text('Issue hierarchy parsing workflow.')
            ->example()
                ->phase('step-1', 'Parse issues via GitHub API and classify based on labels and relations')
                ->phase('step-2', 'Construct hierarchical tree: Epic → Task → Subtask')
                ->phase('step-3', 'Infer missing relations where label patterns indicate likely parent-child mapping');

        $this->guideline('validation-hierarchy')
            ->text('Hierarchy validation requirements.')
            ->example('Every subtask must have exactly one parent task')
            ->example('Every task must have one parent epic unless marked standalone')
            ->example('No cycles in issue linkage graph');

        $this->guideline('fallback-parsing')
            ->text('Fallback actions for incomplete hierarchy data.')
            ->example('If hierarchy data incomplete, default to flat list and flag PM Agent for correction')
            ->example('If milestone missing, auto-assign to current sprint or backlog bucket')
            ->example('If label mismatch detected, reclassify under generic type:task');
    }
}
