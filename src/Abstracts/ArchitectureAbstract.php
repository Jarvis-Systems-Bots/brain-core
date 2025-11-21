<?php

declare(strict_types=1);

namespace BrainCore\Abstracts;

use Bfg\Dto\Dto;
use BrainCore\Support\Brain;

abstract class ArchitectureAbstract extends Dto
{
    /**
     * Get any runtime variable in compile time with default value and processor
     *
     * @param  string  $name
     * @param  mixed|null  $default
     * @return mixed
     */
    public function var(string $name, mixed $default = null): mixed
    {
        if (($env = getenv(strtoupper($name))) !== false) {
            return $env;
        }

        return Brain::getVariable($name, function () use ($name, $default) {
            $defaultClosure = function ($name) use ($default) {
                $defaultMethod = $name . "_default";
                if (method_exists($this, $defaultMethod)) {
                    return call_user_func([$this, $defaultMethod], $default);
                }
                return $default;
            };

            $value = $this->getMeta($name, $defaultClosure);

            if (method_exists($this, $name)) {
                return call_user_func([$this, $name], $value);
            }

            return $value;
        });
    }

    /**
     * Get puzzle variable for architecture
     *
     * @param  string  $name
     * @param  mixed  $value
     * @return string
     */
    public function puzzle(string $name, mixed $value): string
    {
        return puzzle_replace(
            $this->var(...puzzle_params($name)), $value
        );
    }
}
