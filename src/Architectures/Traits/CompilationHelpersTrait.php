<?php

declare(strict_types=1);

namespace BrainCore\Architectures\Traits;

use BrainCore\Compilation\Operator;

trait CompilationHelpersTrait
{
    public function if(string|array $condition, string|array $then, string|array|null $else = null): static
    {
        return $this->addText(
            Operator::if($condition, $then, $else)
        );
    }

    public function forEach(string|array $condition, string|array $body = ''): static
    {
        return $this->addText(
            Operator::forEach($condition, $body)
        );
    }

    public function verify(...$args): static
    {
        return $this->addText(
            Operator::verify(...$args)
        );
    }

    public function task(...$body): static
    {
        return $this->addText(
            Operator::task(...$body)
        );
    }

    public function check(...$args): static
    {
        return $this->addText(
            Operator::check(...$args)
        );
    }

    public function scenario(...$args): static
    {
        return $this->addText(
            Operator::scenario(...$args)
        );
    }

    public function goal(...$args): static
    {
        return $this->addText(
            Operator::goal(...$args)
        );
    }

    public function report(...$args): static
    {
        return $this->addText(
            Operator::report(...$args)
        );
    }

    public function do(...$args): static
    {
        return $this->addText(
            Operator::do(...$args)
        );
    }

    public function chain(...$args): static
    {
        return $this->addText(
            Operator::chain(...$args)
        );
    }

    public function skip(...$args): static
    {
        return $this->addText(
            Operator::skip(...$args)
        );
    }

    public function note(...$args): static
    {
        return $this->addText(
            Operator::note(...$args)
        );
    }

    public function context(...$args): static
    {
        return $this->addText(
            Operator::context(...$args)
        );
    }

    public function output(...$args): static
    {
        return $this->addText(
            Operator::output(...$args)
        );
    }

    public function input(...$args): static
    {
        return $this->addText(
            Operator::input(...$args)
        );
    }

    public function addText(string $text): static
    {
        if ($this->text) {
            $exploded = array_filter(explode(PHP_EOL, $this->text));
            $exploded[] = $text;
            $this->text = PHP_EOL . implode(PHP_EOL, $exploded) . PHP_EOL;
        } else {
            $this->text = $text;
        }
        return $this;
    }
}
