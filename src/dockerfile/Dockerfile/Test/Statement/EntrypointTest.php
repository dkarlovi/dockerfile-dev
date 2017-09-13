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
     * @dataProvider getBuildFixtures
     * @covers       \Dkarlovi\Dockerfile\Statement\Entrypoint::build
     * @covers       \Dkarlovi\Dockerfile\Statement\Entrypoint::dump
     *
     * @uses         \Dkarlovi\Dockerfile\Statement\Entrypoint::__construct
     * @uses         \Dkarlovi\Dockerfile\DockerfileCommand
     *
     * @param array  $spec
     * @param string $fixture
     */
    public function testCanBuildAStatement(array $spec, string $fixture): void
    {
        $entrypoint = Entrypoint::build($spec);

        static::assertEquals($fixture, $entrypoint->dump());
    }

    /**
     * @covers \Dkarlovi\Dockerfile\Statement\Entrypoint::build
     */
    public function testWhenBuildingAStatementTheCommandPropertyIsRequired(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Entrypoint requires a "command" property');

        Entrypoint::build([]);
    }

    /**
     * @covers \Dkarlovi\Dockerfile\Statement\Entrypoint::getIntent
     *
     * @uses   \Dkarlovi\Dockerfile\Statement\Entrypoint::__construct
     */
    public function testStatementIntentIsFullyQualifiedClassName(): void
    {
        $entrypoint = new Entrypoint($this->mockCommand());
        static::assertEquals(Entrypoint::class, $entrypoint->getIntent());
    }

    /**
     * @covers \Dkarlovi\Dockerfile\Statement\Entrypoint::getAmendmentBody
     *
     * @uses   \Dkarlovi\Dockerfile\Statement\Entrypoint::__construct
     */
    public function testAmendmentBodyIsImage(): void
    {
        $command = $this->mockCommand();
        $entrypoint = new Entrypoint($command);
        static::assertEquals($command, $entrypoint->getAmendmentBody());
    }

    /**
     * @covers \Dkarlovi\Dockerfile\Statement\Entrypoint::amendBy
     * @covers \Dkarlovi\Dockerfile\Statement\Entrypoint::<protected>
     *
     * @uses   \Dkarlovi\Dockerfile\Statement\Entrypoint::__construct
     * @uses   \Dkarlovi\Dockerfile\Statement\Entrypoint::dump
     * @uses   \Dkarlovi\Dockerfile\Statement\Entrypoint::getAmendmentBody
     * @uses   \Dkarlovi\Dockerfile\Statement\Entrypoint::getIntent
     * @uses   \Dkarlovi\Dockerfile\Statement\Entrypoint::isApplicableTo
     */
    public function testCanAmendStatementByAmendment(): void
    {
        $statement = new Entrypoint($this->mockCommand('["date"]'));
        $amendment = new Entrypoint($this->mockCommand('["/usr/local/bin/docker-entry"]'));
        $statement->amendBy($amendment);

        static::assertEquals('ENTRYPOINT ["/usr/local/bin/docker-entry"]', $statement->dump());
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
     * @return array
     */
    public function getBuildFixtures(): array
    {
        return [
            [['command' => ['intent' => 'date']], 'ENTRYPOINT ["date"]'],
            [
                [
                    'command' => ['intent' => 'date', 'params' => ['--foo', '--bar']],
                ],
                'ENTRYPOINT ["date", "--foo", "--bar"]',
            ],
        ];
    }

    /**
     * @param string $dump
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|Command
     */
    private function mockCommand(?string $dump = null): Command
    {
        $command = $this
            ->getMockBuilder(Command::class)
            ->getMockForAbstractClass();

        if (null !== $dump) {
            $command
                ->expects(static::any())
                ->method('dumpSchema')
                ->willReturn($dump);
        }

        return $command;
    }
}
