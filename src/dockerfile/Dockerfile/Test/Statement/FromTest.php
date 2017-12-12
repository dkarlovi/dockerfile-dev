<?php

declare(strict_types = 1);

/*
 * This file is part of Dockerfile.
 *
 * (c) Dalibor Karlović <dalibor@flexolabs.io>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Dkarlovi\Dockerfile\Test\Statement;

use Dkarlovi\Dockerfile\Statement\From;
use PHPUnit\Framework\TestCase;

/**
 * Class FromTest.
 */
class FromTest extends TestCase
{
    /**
     * @dataProvider getConstructFixtures
     * @covers       \Dkarlovi\Dockerfile\Statement\From::__construct
     * @covers       \Dkarlovi\Dockerfile\Statement\From::dump
     *
     * @param string $image
     * @param string $fixture
     */
    public function testCanConstructAStatement(string $image, string $fixture): void
    {
        $from = new From($image);

        static::assertEquals($fixture, $from->dump());
    }

    /**
     * @dataProvider getBuildFixtures
     * @covers       \Dkarlovi\Dockerfile\Statement\From::build
     * @covers       \Dkarlovi\Dockerfile\Statement\From::dump
     *
     * @uses         \Dkarlovi\Dockerfile\Statement\From::__construct
     *
     * @param array  $spec
     * @param string $fixture
     */
    public function testCanBuildAStatement(array $spec, string $fixture): void
    {
        $from = From::build($spec);

        static::assertEquals($fixture, $from->dump());
    }

    /** @noinspection PhpMethodNamingConventionInspection */

    /**
     * @covers \Dkarlovi\Dockerfile\Statement\From::build
     */
    public function testWhenBuildingAStatementTheImagePropertyIsRequired(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('From requires an "image" property');

        From::build([]);
    }

    /** @noinspection PhpMethodNamingConventionInspection */

    /**
     * @covers \Dkarlovi\Dockerfile\Statement\From::getIntent
     *
     * @uses   \Dkarlovi\Dockerfile\Statement\From::__construct
     */
    public function testStatementIntentIsFullyQualifiedClassName(): void
    {
        $from = new From('alpine');
        static::assertEquals(From::class, $from->getIntent());
    }

    /**
     * @covers \Dkarlovi\Dockerfile\Statement\From::getAmendmentBody
     *
     * @uses   \Dkarlovi\Dockerfile\Statement\From::__construct
     */
    public function testAmendmentBodyIsImage(): void
    {
        $from = new From('alpine@hash');
        static::assertEquals('alpine@hash', $from->getAmendmentBody());
    }

    /**
     * @covers \Dkarlovi\Dockerfile\Statement\From::amendWith
     * @covers \Dkarlovi\Dockerfile\Statement\From::<protected>
     *
     * @uses   \Dkarlovi\Dockerfile\Statement\From::__construct
     * @uses   \Dkarlovi\Dockerfile\Statement\From::dump
     * @uses   \Dkarlovi\Dockerfile\Statement\From::getAmendmentBody
     * @uses   \Dkarlovi\Dockerfile\Statement\From::getIntent
     * @uses   \Dkarlovi\Dockerfile\Statement\From::isAmendableWith
     */
    public function testCanAmendStatementByAmendment(): void
    {
        $statement = new From('alpine:3.6');
        $amendment = new From('alpine:latest');
        $statement->amendWith($amendment);

        static::assertEquals('FROM alpine:latest', $statement->dump());
    }

    /**
     * @return array
     */
    public function getConstructFixtures(): array
    {
        return [
            ['alpine', 'FROM alpine'],
            ['alpine:latest', 'FROM alpine:latest'],
            ['alpine@hash', 'FROM alpine@hash'],
        ];
    }

    /**
     * @return array
     */
    public function getBuildFixtures(): array
    {
        return [
            [['image' => 'alpine'], 'FROM alpine'],
            [['image' => 'alpine:latest'], 'FROM alpine:latest'],
            [['image' => 'alpine@hash'], 'FROM alpine@hash'],
        ];
    }
}
