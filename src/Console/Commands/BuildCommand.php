<?php

declare(strict_types=1);

namespace BrainCore\Console\Commands;

use Illuminate\Console\Command;

class BuildCommand extends Command
{
    protected $signature = 'build';

    protected $description = 'Build the brain';

    public function handle(): int
    {
        $this->components->success('Build command executed successfully.');

        return 0;
    }
}

