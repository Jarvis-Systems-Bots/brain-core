<?php

declare(strict_types=1);

namespace BrainCore;

class Core
{
    protected string|null $versionCache = null;

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
}
