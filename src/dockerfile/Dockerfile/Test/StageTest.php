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
use Dkarlovi\Dockerfile\Stage;
use Dkarlovi\Dockerfile\Statement;
use Dkarlovi\Dockerfile\Statement\From;
use PHPUnit\Framework\TestCase;

/**
 * Class StageTest.
 */
class StageTest extends TestCase
{
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
        /** @var \PHPUnit_Framework_MockObject_MockObject|From $from */
        $from = $this
            ->getMockBuilder(From::class)
            ->disableOriginalConstructor()
            ->getMock();

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
        /** @var \PHPUnit_Framework_MockObject_MockObject|From $from */
        $from = $this
            ->getMockBuilder(From::class)
            ->disableOriginalConstructor()
            ->getMock();

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
            /** @var \PHPUnit_Framework_MockObject_MockObject|Amendment $amendable */
            $amendable = $this
                ->getMockBuilder(Amendment::class)
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
}
