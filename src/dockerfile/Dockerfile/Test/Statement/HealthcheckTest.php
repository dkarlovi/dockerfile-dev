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

use Dkarlovi\Dockerfile\Statement\Healthcheck;
use PHPUnit\Framework\TestCase;

/**
 * Class HealthcheckTest.
 */
class HealthcheckTest extends TestCase
{
    use CommandMockerTrait;

    /**
     * @param string $command
     * @param string $fixture
     *
     * @dataProvider getConstructFixtures
     * @covers       \Dkarlovi\Dockerfile\Statement\Healthcheck::__construct
     * @covers       \Dkarlovi\Dockerfile\Statement\Healthcheck::dump
     */
    public function testCanConstructAStatement(string $command, string $fixture): void
    {
        $healthcheck = new Healthcheck($this->mockCommand(['schema' => $command]));

        static::assertEquals($fixture, $healthcheck->dump());
    }

    /**
     * @dataProvider getBuildFixtures
     * @covers       \Dkarlovi\Dockerfile\Statement\Healthcheck::build
     * @covers       \Dkarlovi\Dockerfile\Statement\Healthcheck::dump
     *
     * @uses         \Dkarlovi\Dockerfile\Statement\Healthcheck::__construct
     * @uses         \Dkarlovi\Dockerfile\DockerfileCommand
     *
     * @param array  $spec
     * @param string $fixture
     */
    public function testCanBuildAStatement(array $spec, string $fixture): void
    {
        $healthcheck = Healthcheck::build($spec);

        static::assertEquals($fixture, $healthcheck->dump());
    }

    /** @noinspection PhpMethodNamingConventionInspection */

    /**
     * @covers \Dkarlovi\Dockerfile\Statement\Healthcheck::build
     */
    public function testWhenBuildingAStatementTheCommandPropertyIsRequired(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Healthcheck requires a "command" property');

        Healthcheck::build([]);
    }

    /** @noinspection PhpMethodNamingConventionInspection */

    /**
     * @covers \Dkarlovi\Dockerfile\Statement\Healthcheck::getIntent
     *
     * @uses   \Dkarlovi\Dockerfile\Statement\Healthcheck::__construct
     */
    public function testStatementIntentIsFullyQualifiedClassName(): void
    {
        $healthcheck = new Healthcheck($this->mockCommand());
        static::assertEquals(Healthcheck::class, $healthcheck->getIntent());
    }

    /**
     * @covers \Dkarlovi\Dockerfile\Statement\Healthcheck::getAmendmentBody
     *
     * @uses   \Dkarlovi\Dockerfile\Statement\Healthcheck::__construct
     */
    public function testAmendmentBodyIsCommand(): void
    {
        $command = $this->mockCommand();
        $healthcheck = new Healthcheck($command);
        static::assertEquals($command, $healthcheck->getAmendmentBody());
    }

    /**
     * @covers \Dkarlovi\Dockerfile\Statement\Healthcheck::amendWith
     * @covers \Dkarlovi\Dockerfile\Statement\Healthcheck::<protected>
     *
     * @uses   \Dkarlovi\Dockerfile\Statement\Healthcheck::__construct
     * @uses   \Dkarlovi\Dockerfile\Statement\Healthcheck::dump
     * @uses   \Dkarlovi\Dockerfile\Statement\Healthcheck::getAmendmentBody
     * @uses   \Dkarlovi\Dockerfile\Statement\Healthcheck::getIntent
     * @uses   \Dkarlovi\Dockerfile\Statement\Healthcheck::isAmendableWith
     */
    public function testCanAmendStatementByAmendment(): void
    {
        $statement = new Healthcheck($this->mockCommand(['schema' => '["date"]']));
        $amendment = new Healthcheck($this->mockCommand(['schema' => '["/usr/local/bin/docker-entry"]']));
        $statement->amendWith($amendment);

        static::assertEquals('HEALTHCHECK CMD ["/usr/local/bin/docker-entry"]', $statement->dump());
    }

    /**
     * @return array
     */
    public function getConstructFixtures(): array
    {
        return [
            ['date', 'HEALTHCHECK CMD date'],
            ['["date", "foo", "--bar"]', 'HEALTHCHECK CMD ["date", "foo", "--bar"]'],
        ];
    }

    /**
     * @return array
     */
    public function getBuildFixtures(): array
    {
        return [
            [['command' => ['intent' => 'date']], 'HEALTHCHECK CMD ["date"]'],
            [
                [
                    'command' => ['intent' => 'date', 'params' => ['--foo', '--bar']],
                ],
                'HEALTHCHECK CMD ["date", "--foo", "--bar"]',
            ],
        ];
    }
}
