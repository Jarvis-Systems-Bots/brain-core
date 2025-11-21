<?php

declare(strict_types=1);

namespace BrainCore;

use Bfg\Dto\Dto;

class Core
{
    protected string|null $versionCache = null;

    protected array $variables = [];

    protected Dto|null $currentCompileDto = null;

    public function basePath(string $path = '', bool $relative = false): string
    {
        if (! $relative) {
            $cwd = getcwd();

            if ($cwd === false) {
                throw new \RuntimeException('Unable to get current working directory.');
            }
        } else {
            $cwd = '';
        }

        return $cwd . ($relative ? '' : DS) . ltrim($path, DS);
    }

    public function version(): string|null
    {
        if ($this->versionCache !== null) {
            return $this->versionCache;
        }

        $composerPath = dirname(__DIR__) . DS . 'composer.json';
        if (is_file($composerPath)) {
            $json = json_decode((string) file_get_contents($composerPath), true);
            if (is_array($json) && isset($json['version']) && is_string($json['version'])) {
                return $this->versionCache = $json['version'];
            }
        }

        return $this->versionCache = null;
    }

    public function setVariable(string $name, mixed $value): void
    {
        $this->variables[$name] = $value;
    }

    /**
     * @param  string  $name
     * @param  mixed|null  $default
     * @return scalar
     */
    public function getVariable(string $name, mixed $default = null): mixed
    {
        return $this->variables[$name] ?? value($default);
    }

    public function mergeVariables(array ...$arrays): void
    {
        foreach ($arrays as $array) {
            foreach ($array as $key => $value) {
                $this->variables[$key] = $value;
            }
        }
    }

    public function setCurrentCompileDto(Dto|null $dto): void
    {
        $this->currentCompileDto = $dto;
    }

    public function getCurrentCompileDto(): Dto|null
    {
        return $this->currentCompileDto;
    }

    public function getVariables(): array
    {
        return $this->variables;
    }
}
