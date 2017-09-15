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
}
