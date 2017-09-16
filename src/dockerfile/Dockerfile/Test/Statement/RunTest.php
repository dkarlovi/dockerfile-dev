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

namespace Dkarlovi\Dockerfile\Test\Statement;

use Dkarlovi\Dockerfile\Command;
use Dkarlovi\Dockerfile\Statement\Run;
use PHPUnit\Framework\TestCase;

/**
 * Class RunTest.
 */
class RunTest extends TestCase
{
    use CommandMockerTrait;

    /**
     * @dataProvider getConstructFixtures
     * @covers       \Dkarlovi\Dockerfile\Statement\Run::__construct
     * @covers       \Dkarlovi\Dockerfile\Statement\Run::dump
     *
     * @param string[] $commands
     * @param string   $fixture
     */
    public function testCanConstructAStatement(array $commands, string $fixture): void
    {
        $run = new Run($this->mockCommands($commands));

        static::assertEquals($fixture, $run->dump());
    }

    /**
     * @return array
     */
    public function getConstructFixtures(): array
    {
        return [
            [['date'], 'RUN date'],
            [['date', 'echo foo'], "RUN date && \\\n    echo foo"],
        ];
    }

    /**
     * @param string[] $dumps
     *
     * @return Command[]
     */
    private function mockCommands(array $dumps): array
    {
        $commands = [];
        foreach ($dumps as $dump) {
            $commands[] = $this->mockCommand(['dump' => $dump]);
        }

        return $commands;
    }
}
