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

namespace Dkarlovi\Dockerfile\Builder\Console;

use Dkarlovi\Dockerfile\Builder\Command\BuildCommand;
use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Command\Command;

/**
 * Class Application.
 */
class Application extends BaseApplication
{
    /**
     * Application constructor.
     */
    public function __construct()
    {
        parent::__construct('Dockerfile Builder');
    }

    /**
     * Gets the default commands that should always be available.
     *
     * @throws \Symfony\Component\Console\Exception\LogicException
     *
     * @return Command[] An array of default Command instances
     */
    protected function getDefaultCommands(): array
    {
        return \array_merge(
            parent::getDefaultCommands(),
            [
                new BuildCommand(),
            ]
        );
    }
}
