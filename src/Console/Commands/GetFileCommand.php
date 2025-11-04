<?php

declare(strict_types=1);

namespace BrainCore\Console\Commands;

use Bfg\Dto\Dto;
use BrainCore\Architectures\ArchetypeArchitecture;
use BrainCore\Merger;
use BrainCore\Support\Brain;
use BrainCore\TomlBuilder;
use BrainCore\XmlBuilder;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Symfony\Component\Yaml\Yaml;

class GetFileCommand extends Command
{
    protected $signature = 'get:file 
        {files : The file to read}
        {--json : Output in JSON format}
        {--xml : Output in XML format}
        {--yaml : Output in YAML format}
        {--toml : Output in TOML format}
        {--meta : Output only meta information}
    ';

    protected $description = 'Compile the Brain configurations files';

    public function handle(): int
    {
        $isXml = $this->option('xml');
        $isJson = $this->option('json');
        $isYaml = $this->option('yaml');
        $isToml = $this->option('toml');
        $isMeta = $this->option('meta');
        $files = explode(" && ", $this->argument('files'));
        $result = [];

        foreach ($files as $file) {
            /** @var Dto|int $class */
            $class = $this->getClassPathByFile($file);

            if (is_int($class)) {
                continue;
            }
//            $fromEmptyStart = microtime(true);
            $dto = $class::fromEmpty();
//            $fromEmptyTime = round((microtime(true) - $fromEmptyStart) * 1000, 2);

//            error_log(sprintf(
//                "fromEmpty: %sms",
//                $fromEmptyTime
//            ));

            $classBasename = class_basename($class);
            $defaultData = [
                'id' => Str::snake($classBasename, '-'),
                'file' => $file,
                'class' => $class,
                'meta' => $dto->getMeta(),
                'namespace' => str_replace('\\' . $classBasename, '', $class),
                'classBasename' => $classBasename,
            ];

            if (! $isMeta) {
                if ($dto instanceof ArchetypeArchitecture) {
                    $structure = Merger::from($dto);
                } else {
                    $structure = $dto->toArray();
                }
                if ($isXml) {
                    $result[$file] = [
                        ...$defaultData,
                        'format' => 'xml',
                        'structure' => XmlBuilder::from($structure),
                    ];
                } else if ($isJson) {
                    $result[$file] = [
                        ...$defaultData,
                        'format' => 'json',
                        'structure' => $structure,
                    ];
                } elseif ($isYaml) {
                    $result[$file] = [
                        ...$defaultData,
                        'format' => 'yaml',
                        'structure' => Yaml::dump($structure, 512, 2, Yaml::DUMP_OBJECT_AS_MAP),
                    ];
                } elseif ($isToml) {
                    $result[$file] = [
                        ...$defaultData,
                        'format' => 'toml',
                        'structure' => TomlBuilder::from($structure),
                    ];
                } else {
                    dump([
                        ...$defaultData,
                        'format' => 'dump',
                        'structure' => $structure,
                    ]);
                    $result = false;
                }
            } else {
                $result[$file] = [
                    ...$defaultData,
                    'format' => 'meta',
                ];
            }
        }

        if ($result) {
            echo json_encode($result, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
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
                return ERROR;
            }
        } else {
            return ERROR;
        }
    }
}

