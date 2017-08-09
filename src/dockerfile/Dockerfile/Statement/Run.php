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
 * Class Run.
 */
class Run implements Statement
{
    /**
     * @var Command[]
     */
    private $commands;

    /**
     * Run constructor.
     *
     * @param \Dkarlovi\Dockerfile\Command[] $commands
     */
    public function __construct(array $commands)
    {
        $this->commands = $commands;
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
        $commands = [];

        if ($spec['commands']) {
            foreach ($spec['commands'] as $command) {
                $commands[] = Command::build($command);
            }
        }

        return new self($commands);
    }
}
