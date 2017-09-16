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

use Dkarlovi\Dockerfile\Statement\Env;
use PHPUnit\Framework\TestCase;

/**
 * Class EnvTest.
 */
class EnvTest extends TestCase
{
    /**
     * @dataProvider getConstructFixtures
     * @covers       \Dkarlovi\Dockerfile\Statement\Env::__construct
     * @covers       \Dkarlovi\Dockerfile\Statement\Env::dump
     *
     * @param string           $name
     * @param string|int|float $value
     * @param string           $fixture
     */
    public function testCanConstructAStatement(string $name, $value, string $fixture): void
    {
        $env = new Env($name, $value);

        static::assertEquals($fixture, $env->dump());
    }

    /**
     * @dataProvider getBuildFixtures
     * @covers       \Dkarlovi\Dockerfile\Statement\Env::build
     * @covers       \Dkarlovi\Dockerfile\Statement\Env::dump
     *
     * @uses         \Dkarlovi\Dockerfile\Statement\Env::__construct
     *
     * @param array  $spec
     * @param string $fixture
     */
    public function testCanBuildAStatement(array $spec, string $fixture): void
    {
        $env = Env::build($spec);

        static::assertEquals($fixture, $env->dump());
    }

    /** @noinspection PhpMethodNamingConventionInspection */

    /**
     * @covers \Dkarlovi\Dockerfile\Statement\Env::build
     */
    public function testWhenBuildingAStatementTheNamePropertyIsRequired(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Env requires a "name" property');

        Env::build([]);
    }

    /** @noinspection PhpMethodNamingConventionInspection */

    /**
     * @covers \Dkarlovi\Dockerfile\Statement\Env::build
     */
    public function testWhenBuildingAStatementTheValuePropertyIsRequired(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Env requires a "value" property');

        Env::build(['name' => 'foo']);
    }

    /**
     * @covers \Dkarlovi\Dockerfile\Statement\Env::getIntent
     *
     * @uses   \Dkarlovi\Dockerfile\Statement\Env::__construct
     */
    public function testStatementIntentIsName(): void
    {
        $env = new Env('foo', '/app/bar');
        static::assertEquals('foo', $env->getIntent());
    }

    /**
     * @covers \Dkarlovi\Dockerfile\Statement\Env::getAmendmentBody
     *
     * @uses   \Dkarlovi\Dockerfile\Statement\Env::__construct
     */
    public function testAmendmentBodyIsValue(): void
    {
        $env = new Env('foo', '/app/bar');
        static::assertEquals('/app/bar', $env->getAmendmentBody());
    }

    /**
     * @covers \Dkarlovi\Dockerfile\Statement\Env::amendWith
     * @covers \Dkarlovi\Dockerfile\Statement\Env::isAmendableWith
     * @covers \Dkarlovi\Dockerfile\Statement\Env::<protected>
     *
     * @uses   \Dkarlovi\Dockerfile\Statement\Env::__construct
     * @uses   \Dkarlovi\Dockerfile\Statement\Env::dump
     * @uses   \Dkarlovi\Dockerfile\Statement\Env::getAmendmentBody
     * @uses   \Dkarlovi\Dockerfile\Statement\Env::getIntent
     */
    public function testCanAmendStatementByAmendment(): void
    {
        $statement = new Env('foo', '/app/bar');
        $amendment = new Env('foo', '/app/bat');
        $statement->amendWith($amendment);

        static::assertEquals('ENV foo /app/bat', $statement->dump());
    }

    /**
     * @return array
     */
    public function getConstructFixtures(): array
    {
        return [
            ['abc', '/app/abc', 'ENV abc /app/abc'],
            ['abc', 123, 'ENV abc 123'],
            ['abc', 12.3, 'ENV abc 12.3'],
        ];
    }

    /**
     * @return array
     */
    public function getBuildFixtures(): array
    {
        return [
            [['name' => 'abc', 'value' => '/app/abc'], 'ENV abc /app/abc'],
            [['name' => 'abc', 'value' => 123], 'ENV abc 123'],
            [['name' => 'abc', 'value' => 12.3], 'ENV abc 12.3'],
        ];
    }
}
