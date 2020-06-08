<?php

declare(strict_types=1);

namespace Imoisey\Makebuilder;

class Target
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var Target[]
     */
    protected $prerequisites = [];

    /**
     * @var array 
     */
    protected $commands = [];

    /**
     * Target constructor.
     * 
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * Get target name
     *
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * Add prerequisite for target
     *
     * @param string $prerequisite
     */
    public function addPrerequisite(string $prerequisite) : void
    {
        $this->prerequisites[] = $prerequisite;
    }

    /**
     * Add command for target
     *
     * @param string $command
     */
    public function addCommand(string $command) : void
    {
        $this->commands[] = $command;
    }

    /**
     * Render to string
     *
     * @return string
     */
    public function __toString() : string
    {
        $commands = '';
        if (!empty($this->commands)) {
            $commands = sprintf("\n\t%s", $this->renderCommands());
        }

        return sprintf("%s: %s%s", $this->getName(), $this->renderPrerequisite(), $commands);
    }

    /**
     * Return list prerequisites for render
     *
     * @return string
     */
    private function renderPrerequisite() : string
    {
        return implode(' ', $this->prerequisites);
    }

    /**
     * Return list commands for render
     *
     * @return string
     */
    private function renderCommands() : string
    {
        return implode("\n\t", $this->commands);
    }

}