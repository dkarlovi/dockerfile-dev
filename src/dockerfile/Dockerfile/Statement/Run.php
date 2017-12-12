<?php

declare(strict_types = 1);

/*
 * This file is part of Dockerfile.
 *
 * (c) Dalibor Karlović <dalibor@flexolabs.io>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Dkarlovi\Dockerfile\Statement;

use Dkarlovi\Dockerfile\Amendable;
use Dkarlovi\Dockerfile\Amendable\AmendableCollectionTrait;
use Dkarlovi\Dockerfile\Amendable\AmendableTrait;
use Dkarlovi\Dockerfile\AmendableCollection;
use Dkarlovi\Dockerfile\Amendment;
use Dkarlovi\Dockerfile\Command;
use Dkarlovi\Dockerfile\DockerfileCommand;
use Dkarlovi\Dockerfile\Statement;
use Webmozart\Assert\Assert;

/**
 * Class Run.
 */
class Run implements AmendableCollection, Statement
{
    use AmendableTrait;
    use AmendableCollectionTrait;

    /**
     * @var Command[]
     */
    private $commands;

    /**
     * Run constructor.
     *
     * @param Command[] $commands
     */
    public function __construct(array $commands)
    {
        foreach ($commands as $command) {
            $this->addCommand($command);
        }
    }

    /**
     * @return string
     */
    public function dump(): string
    {
        $out = [];
        foreach ($this->commands as $command) {
            $out[] = $command->dump();
        }

        return 'RUN '.\implode(' && \\'."\n    ", $out);
    }

    /**
     * @param string[][][] $spec
     *
     * @return Run
     */
    public static function build(array $spec): self
    {
        Assert::keyExists($spec, 'commands', 'Run statement requires a "commands" property');

        $commands = [];
        foreach ($spec['commands'] as $command) {
            $commands[] = DockerfileCommand::build($command);
        }

        return new self($commands);
    }

    /**
     * @return string
     */
    public function getIntent(): string
    {
        // TODO: Implement getIntent() method.
    }

    /**
     * @return mixed
     */
    public function getAmendmentBody()
    {
        // TODO: Implement getAmendmentBody() method.
    }

    /**
     * @param Amendment $amendment
     */
    protected function amendSelfWith(Amendment $amendment): void
    {
        // TODO: Implement amendSelfBy() method.
    }

    /**
     * @return Amendable[]
     */
    protected function getAmendableCollection(): array
    {
        /** @var Amendable[] $collection */
        /** @noinspection OneTimeUseVariablesInspection */
        $collection = $this->commands;

        return $collection;
    }

    /**
     * @param Command $command
     */
    private function addCommand(Command $command): void
    {
        $this->commands[] = $command;
    }
}
