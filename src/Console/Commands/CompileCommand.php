<?php

declare(strict_types=1);

namespace BrainCore\Console\Commands;

use BrainCore\Merger;
use BrainCore\XmlBuilder;
use BrainNode\Brain;
use Illuminate\Console\Command;

class CompileCommand extends Command
{
    protected $signature = 'compile';

    protected $description = 'Compile the Brain configurations files';

    public function handle(): int
    {
        $dto = Brain::fromEmpty();
        $structure = Merger::from($dto)->handle();
        $xml = XmlBuilder::from($structure)->build();
        dd($xml);

        //$a = Brain::run();

//        $this->components->success('Compile command executed successfully!');
        //$this->components->success($a);

        return 0;
    }
}

