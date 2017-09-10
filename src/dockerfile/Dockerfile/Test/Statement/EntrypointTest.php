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
use Dkarlovi\Dockerfile\Statement\Entrypoint;
use PHPUnit\Framework\TestCase;

/**
 * Class EntrypointTest.
 */
class EntrypointTest extends TestCase
{
    /**
     * @param string $command
     * @param string $fixture
     *
     * @dataProvider getConstructFixtures
     * @covers       \Dkarlovi\Dockerfile\Statement\Entrypoint::__construct
     * @covers       \Dkarlovi\Dockerfile\Statement\Entrypoint::dump
     */
    public function testCanConstructAStatement(string $command, string $fixture): void
    {
        $entrypoint = new Entrypoint($this->mockCommand($command));

        static::assertEquals($fixture, $entrypoint->dump());
    }

    /**
     * @return array
     */
    public function getConstructFixtures(): array
    {
        return [
            ['date', 'ENTRYPOINT date'],
            ['["date", "foo", "--bar"]', 'ENTRYPOINT ["date", "foo", "--bar"]'],
        ];
    }

    /**
     * @param string $dump
     *
     * @return Command
     */
    private function mockCommand(string $dump): Command
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|Command $command */
        $command = $this
            ->getMockBuilder(Command::class)
            ->disableOriginalConstructor()
            ->getMock();
        $command
            ->expects(static::once())
            ->method('dumpSchema')
            ->willReturn($dump);

        return $command;
    }
}
