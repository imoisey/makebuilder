<?php

declare(strict_types=1);

namespace Imoisey\Makebuilder;

class MakeBuilder
{
    /**
     * @var array
     */
    private $variables = [];

    /**
     * @var Target[]
     */
    private $targets = [];

    /**
     * @var array
     */
    private $commands = [];

    /**
     * Create target to list
     *
     * @param string $name
     * @param callable $func
     * @return MakeBuilder
     */
    public function createTarget(string $name, callable $func): self
    {
        $target = new Target($name);

        $func($target);

        $this->addTarget($target);

        return $this;
    }

    /**
     * Add target to list
     *
     * @param Target $target
     * @return MakeBuilder
     */
    public function addTarget(Target $target): self
    {
        $this->targets[$target->getName()] = $target;

        return $this;
    }

    /**
     * Get target by name
     *
     * @param string $name
     * @return Target
     */
    public function getTarget(string $name): Target
    {
        if (!isset($this->targets[$name])) {
            throw new \RuntimeException("Target '{$name}' not found.");
        }

        return $this->targets[$name];
    }

    /**
     * Add variable to list
     *
     * @param string $name
     * @param string $value
     * @return MakeBuilder
     */
    public function addVariable(string $name, string $value): self
    {
        $name = $this->replaceSpaceInVariable($name);

        $this->variables[$name] = $value;

        return $this;
    }

    /**
     * Add command
     *
     * @param string $command
     * @return $this
     */
    public function addCommand(string $command): self
    {
        $this->commands[] = $command;

        return $this;
    }

    /**
     * Building to file
     *
     * @param string $filepath
     * @return bool
     */
    public function build(string $filepath): bool
    {
        $components = [];
        
        if ($variables = $this->buildVariables()) {
            $components[] = $variables;
        }

        if ($commands = $this->buildCommands()) {
            $components[] = $commands;
        }

        if ($targets = $this->buildTargets()) {
            $components[] = $targets;
        }

        $exportContent = implode("\n", $components);

        if (file_put_contents($filepath, $exportContent)) {
            return true;
        }

        throw new \RuntimeException('Build error!');
    }

    /**
     * Building variables
     *
     * @return string
     */
    private function buildVariables(): string
    {
        $variables = $this->variables;

        array_walk($variables, static function (&$value, $name) {
            $value = sprintf('%s=%s', $name, $value);
        });

        return implode("\n", $variables);
    }

    /**
     * Building commands
     *
     * @return string
     */
    private function buildCommands(): string
    {
        return implode("\n", $this->commands);
    }

    /**
     * Building targets
     *
     * @return string
     */
    private function buildTargets(): string
    {
        return implode("\n", $this->targets);
    }

    /**
     * Replace space in variable name
     *
     * @param string $name
     * @return string
     */
    private function replaceSpaceInVariable(string $name): string
    {
        return str_replace(' ', '_', $name);
    }

}