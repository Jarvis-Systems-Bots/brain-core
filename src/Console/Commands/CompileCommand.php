<?php

declare(strict_types=1);

namespace BrainCore\Console\Commands;

use BrainCore\Cortex\IronRules;
use BrainNode\Brain;
use Illuminate\Console\Command;

class CompileCommand extends Command
{
    protected $signature = 'compile';

    protected $description = 'Compile the Brain configurations files';

    public function handle(): int
    {
        $dto = Brain::from();
        $dto->ironRules->rule('test')->id('12')->why('because')->onViolation('do something')->critical();
        dd($dto->toJson(JSON_PRETTY_PRINT));

        //$a = Brain::run();

//        $this->components->success('Compile command executed successfully!');
        //$this->components->success($a);

        return 0;
    }
}

