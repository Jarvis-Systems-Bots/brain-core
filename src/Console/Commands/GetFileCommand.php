<?php

declare(strict_types=1);

namespace BrainCore\Console\Commands;

use Bfg\Dto\Dto;
use BrainCore\Merger;
use BrainCore\Support\Brain;
use BrainCore\XmlBuilder;
use Illuminate\Console\Command;
use Symfony\Component\Yaml\Yaml;

class GetFileCommand extends Command
{
    protected $signature = 'get:file 
        {file : The file to read}
        {--json : Output in JSON format}
        {--xml : Output in XML format}
        {--yaml : Output in YAML format}
    ';

    protected $description = 'Compile the Brain configurations files';

    public function handle(): int
    {
        /** @var Dto|int $class */
        $class = $this->getClassPathByFile($this->argument('file'));

        if (is_int($class)) {
            return $class;
        }

        $isXml = $this->option('xml');
        $isJson = $this->option('json');
        $isYaml = $this->option('yaml');
        $dto = $class::fromEmpty();
        $structure = Merger::from($dto);

        if ($isXml) {
            echo XmlBuilder::from($structure);
        } else if ($isJson) {
            echo json_encode($structure, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
        } elseif ($isYaml) {
            echo Yaml::dump($structure, 4, 2);
        } else {
            dump($structure);
        }

        return OK;
    }

    /**
     * Get class path by file.
     *
     * @param  string  $file
     * @return string|int
     */
    protected function getClassPathByFile(string $file): string|int
    {
        $file = Brain::basePath($file);

        if (!file_exists($file)) {
            $this->components->error("File {$file} does not exist.");
            return ERROR;
        }

        $content = file_get_contents($file);
        preg_match('/namespace\s+([a-zA-Z0-9_\\\\]+);/', $content, $namespaceMatches);
        preg_match('/class\s+([a-zA-Z0-9_]+)\s*/', $content, $classMatches);

        if (isset($namespaceMatches[1]) && isset($classMatches[1])) {
            $class = $namespaceMatches[1] . '\\' . $classMatches[1];
            if (class_exists($class) && is_subclass_of($class,Dto::class)) {
                return $class;
            } else {
                $this->components->error("Class {$class} does not exist.");
                return ERROR;
            }
        } else {
            $this->components->error("Could not determine class name from file {$file}.");
            return ERROR;
        }
    }
}

