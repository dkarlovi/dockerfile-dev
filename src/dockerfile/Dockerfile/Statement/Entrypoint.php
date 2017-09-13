<?php

declare(strict_types = 1);

/*
 * This file is part of Dockerfile.
 *
 * (c) Dalibor Karlović <dkarlovi@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Dkarlovi\Dockerfile\Statement;

use Dkarlovi\Dockerfile\Amendable\AmendableTrait;
use Dkarlovi\Dockerfile\Amendment;
use Dkarlovi\Dockerfile\Command;
use Dkarlovi\Dockerfile\DockerfileCommand;
use Dkarlovi\Dockerfile\Statement;
use Webmozart\Assert\Assert;

/**
 * Class Entrypoint.
 */
class Entrypoint implements Statement
{
    use AmendableTrait;

    /**
     * @var Command
     */
    private $command;

    /**
     * Entrypoint constructor.
     *
     * @param Command $command
     */
    public function __construct(Command $command)
    {
        $this->command = $command;
    }

    /**
     * @return string
     */
    public function dump(): string
    {
        return \sprintf('ENTRYPOINT %1$s', $this->command->dumpSchema());
    }

    /**
     * @param array $spec
     *
     * @return Entrypoint
     */
    public static function build(array $spec): self
    {
        Assert::keyExists($spec, 'command', 'Entrypoint requires a "command" property');
        $command = DockerfileCommand::build($spec['command']);

        return new self($command);
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
    protected function amendSelfBy(Amendment $amendment): void
    {
        // TODO: Implement amendSelfBy() method.
    }
}
