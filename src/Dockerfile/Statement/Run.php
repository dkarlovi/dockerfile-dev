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

use Dkarlovi\Dockerfile\Statement;
use Dkarlovi\Dockerfile\Statement\Run\Command;

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
     * @param Command[] $commands
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

        return 'RUN '.\implode(' && ', $out);
    }
}
