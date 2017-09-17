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

namespace Dkarlovi\Dockerfile\Test;

use Dkarlovi\Dockerfile\Amendment;
use Dkarlovi\Dockerfile\Exception\InvalidArgumentException;
use Dkarlovi\Dockerfile\Stage;
use Dkarlovi\Dockerfile\Statement;
use Dkarlovi\Dockerfile\Statement\From;
use PHPUnit\Framework\TestCase;

/**
 * Class StageTest.
 */
class StageTest extends TestCase
{
    /**
     * @dataProvider getConstructFixtures
     * @covers       \Dkarlovi\Dockerfile\Stage::__construct
     * @covers       \Dkarlovi\Dockerfile\Stage::dump
     * @covers       \Dkarlovi\Dockerfile\Stage::<private>
     *
     * @param string      $image
     * @param string      $fixture
     * @param array|null  $statements
     * @param string|null $alias
     */
    public function testCanConstructAStage(
        string $image,
        string $fixture,
        ?array $statements = null,
        ?string $alias = null
    ): void {
        $from = $this->mockFrom($image);
        $stage = new Stage($from, $statements, $alias);

        self::assertEquals($fixture, $stage->dump());
    }

    /** @noinspection PhpMethodNamingConventionInspection */

    /**
     * @covers \Dkarlovi\Dockerfile\Stage::amendFirstAmendableWith
     * @covers \Dkarlovi\Dockerfile\Stage::<protected>
     * @covers \Dkarlovi\Dockerfile\Stage::<private>
     *
     * @uses   \Dkarlovi\Dockerfile\Stage::__construct
     */
    public function testCanAmendFirstMatchingStatement(): void
    {
        $from = $this->mockFrom();

        /** @var \PHPUnit_Framework_MockObject_MockObject|Amendment $amendment */
        $amendment = $this
            ->getMockBuilder(Amendment::class)
            ->getMockForAbstractClass();

        /** @var Statement[] $collection */
        $collection = $this->mockAmendableCollection([
            [false, false],
            [true, true],
            [false, false],
            [true, false],
            [false, false],
        ], $amendment);
        $stage = new Stage($from, $collection);
        $stage->amendFirstAmendableWith($amendment);
    }

    /** @noinspection PhpMethodNamingConventionInspection */

    /**
     * @covers \Dkarlovi\Dockerfile\Stage::amendLastAmendableWith
     * @covers \Dkarlovi\Dockerfile\Stage::<protected>
     * @covers \Dkarlovi\Dockerfile\Stage::<private>
     *
     * @uses   \Dkarlovi\Dockerfile\Stage::__construct
     */
    public function testCanAmendLastMatchingStatement(): void
    {
        $from = $this->mockFrom();

        /** @var \PHPUnit_Framework_MockObject_MockObject|Amendment $amendment */
        $amendment = $this
            ->getMockBuilder(Amendment::class)
            ->getMockForAbstractClass();

        /** @var Statement[] $collection */
        $collection = $this->mockAmendableCollection([
            [false, false],
            [true, false],
            [true, false],
            [true, true],
            [false, false],
        ], $amendment);
        $stage = new Stage($from, $collection);
        $stage->amendLastAmendableWith($amendment);
    }

    /** @noinspection PhpMethodNamingConventionInspection */

    /**
     * @covers \Dkarlovi\Dockerfile\Stage::amendLastAmendableWith
     * @covers \Dkarlovi\Dockerfile\Stage::<protected>
     * @covers \Dkarlovi\Dockerfile\Stage::<private>
     *
     * @uses   \Dkarlovi\Dockerfile\Stage::__construct
     */
    public function testPassingAnAmendmentWithNoMatchThrowsAnInvalidArgumentException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('No amendable collection item found');

        $from = $this->mockFrom();

        /** @var \PHPUnit_Framework_MockObject_MockObject|Amendment $amendment */
        $amendment = $this
            ->getMockBuilder(Amendment::class)
            ->getMockForAbstractClass();

        /** @var Statement[] $collection */
        $collection = $this->mockAmendableCollection([
            [false, false],
            [false, false],
            [false, false],
            [false, false],
            [false, false],
        ], $amendment);
        $stage = new Stage($from, $collection);
        $stage->amendLastAmendableWith($amendment);
    }

    /**
     * @return array
     */
    public function getConstructFixtures(): array
    {
        return [
            ['FROM foo', 'FROM foo'."\n"],
            ['FROM foo', 'FROM foo AS bar'."\n", null, 'bar'],
        ];
    }

    /**
     * @param bool[][]  $configs
     * @param Amendment $amendment
     *
     * @return array
     */
    private function mockAmendableCollection(array $configs, Amendment $amendment): array
    {
        $collection = [];
        foreach ($configs as [$applicable, $amend]) {
            /** @var \PHPUnit_Framework_MockObject_MockObject|Statement $amendable */
            $amendable = $this
                ->getMockBuilder(Statement::class)
                ->setMethods(['isAmendableWith', 'amendWith'])
                ->getMockForAbstractClass();
            $amendable
                ->expects(static::any())
                ->method('isAmendableWith')
                ->with($amendment)
                ->willReturn($applicable);
            if (true === $amend) {
                $amendable
                    ->expects(static::once())
                    ->method('amendWith')
                    ->with($amendment);
            } else {
                $amendable
                    ->expects(static::never())
                    ->method('amendWith')
                    ->with($amendment);
            }
            $collection[] = $amendable;
        }

        return $collection;
    }

    /**
     * @param null|string $dump
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|From
     */
    private function mockFrom(?string $dump = null): From
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|From $from */
        $from = $this
            ->getMockBuilder(From::class)
            ->disableOriginalConstructor()
            ->getMock();

        if (null !== $dump) {
            $from
                ->expects(static::once())
                ->method('dump')
                ->willReturn($dump);
        }

        return $from;
    }
}
