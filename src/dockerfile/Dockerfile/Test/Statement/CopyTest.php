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

use Dkarlovi\Dockerfile\Statement\Copy;
use PHPUnit\Framework\TestCase;

/**
 * Class CopyTest.
 */
class CopyTest extends TestCase
{
    /**
     * @dataProvider getConstructFixtures
     * @covers       \Dkarlovi\Dockerfile\Statement\Copy::__construct
     * @covers       \Dkarlovi\Dockerfile\Statement\Copy::dump
     *
     * @param string|string[] $source
     * @param string          $target
     * @param string          $fixture
     * @param null|string     $from
     */
    public function testCanConstructAStatement($source, string $target, string $fixture, ?string $from = null): void
    {
        $copy = new Copy($source, $target, $from);

        static::assertEquals($fixture, $copy->dump());
    }

    /**
     * @dataProvider getBuildFixtures
     * @covers       \Dkarlovi\Dockerfile\Statement\Copy::build
     * @covers       \Dkarlovi\Dockerfile\Statement\Copy::dump
     *
     * @uses         \Dkarlovi\Dockerfile\Statement\Copy::__construct
     *
     * @param array  $spec
     * @param string $fixture
     */
    public function testCanBuildAStatement(array $spec, string $fixture): void
    {
        $copy = Copy::build($spec);

        static::assertEquals($fixture, $copy->dump());
    }

    /**
     * @covers \Dkarlovi\Dockerfile\Statement\Copy::build
     */
    public function testWhenBuildingAStatementTheSourcePropertyIsRequired(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Copy requires a "source" property');

        Copy::build([]);
    }

    /**
     * @covers \Dkarlovi\Dockerfile\Statement\Copy::build
     */
    public function testWhenBuildingAStatementTheTargetPropertyIsRequired(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Copy requires a "target" property');

        Copy::build(['source' => 'foo']);
    }

    /**
     * @covers \Dkarlovi\Dockerfile\Statement\Copy::getIntent
     *
     * @uses   \Dkarlovi\Dockerfile\Statement\Copy::__construct
     */
    public function testStatementIntentIsTargetWithOptionalFrom(): void
    {
        $copy1 = new Copy('foo', '/app/bar');
        static::assertEquals('/app/bar', $copy1->getIntent());

        $copy2 = new Copy('foo', '/app/bar', 'bat');
        static::assertEquals('/app/bar_bat', $copy2->getIntent());
    }

    /**
     * @covers \Dkarlovi\Dockerfile\Statement\Copy::getAmendmentBody
     *
     * @uses   \Dkarlovi\Dockerfile\Statement\Copy::__construct
     */
    public function testAmendmentBodyIsSource(): void
    {
        $copy1 = new Copy('foo', '/app/bar');
        static::assertEquals(['foo'], $copy1->getAmendmentBody());

        $copy2 = new Copy(['foo', 'bar'], '/app/bar');
        static::assertEquals(['foo', 'bar'], $copy2->getAmendmentBody());
    }

    /**
     * @covers \Dkarlovi\Dockerfile\Statement\Copy::amendWith()
     * @covers \Dkarlovi\Dockerfile\Statement\Copy::isAmendableWith
     * @covers \Dkarlovi\Dockerfile\Statement\Copy::<protected>
     *
     * @uses   \Dkarlovi\Dockerfile\Statement\Copy::__construct
     * @uses   \Dkarlovi\Dockerfile\Statement\Copy::dump
     * @uses   \Dkarlovi\Dockerfile\Statement\Copy::getAmendmentBody
     * @uses   \Dkarlovi\Dockerfile\Statement\Copy::getIntent
     */
    public function testCanAmendStatementByAmendment(): void
    {
        $statement = new Copy('foo', '/app/bar');
        $amendment = new Copy('bar', '/app/bar');
        $statement->amendWith($amendment);

        static::assertEquals('COPY foo bar /app/bar/', $statement->dump());
    }

    /**
     * @return array
     */
    public function getConstructFixtures(): array
    {
        return [
            ['abc', '/app/abc', 'COPY abc /app/abc'],
            [['abc', 'bcd'], '/app/abc', 'COPY abc bcd /app/abc/'],
            [['abc', 'bcd'], '/app/abc', 'COPY --from=previous abc bcd /app/abc/', 'previous'],
        ];
    }

    /**
     * @return array
     */
    public function getBuildFixtures(): array
    {
        return [
            [['source' => 'abc', 'target' => '/app/abc'], 'COPY abc /app/abc'],
            [['source' => ['abc', 'bcd'], 'target' => '/app/abc'], 'COPY abc bcd /app/abc/'],
            [
                ['source' => ['abc', 'bcd'], 'target' => '/app/abc', 'from' => 'previous'],
                'COPY --from=previous abc bcd /app/abc/',
            ],
        ];
    }
}
