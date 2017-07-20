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

use Dkarlovi\Dockerfile\Command;
use Dkarlovi\Dockerfile\Statement;

/**
 * Class Healthcheck.
 */
class Healthcheck implements Statement
{
    /**
     * @var Command
     */
    private $command;

    /**
     * @var string[]|null
     */
    private $options;

    /**
     * Healthcheck constructor.
     *
     * @param Command       $command
     * @param string[]|null $options
     */
    public function __construct(Command $command, ?array $options = null)
    {
        $this->command = $command;
        $this->options = $options;
    }

    /**
     * @return string
     */
    public function dump(): string
    {
        return \sprintf('HEALTHCHECK CMD %1$s', $this->command->dumpSchema());
    }
}
