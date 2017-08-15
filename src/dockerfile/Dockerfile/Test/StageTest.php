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
use Dkarlovi\Dockerfile\Statement\From;
use PHPUnit\Framework\TestCase;

/**
 * Class StageTest.
 */
class StageTest extends TestCase
{
    /**
     * @covers \Dkarlovi\Dockerfile\Stage::amendFirstBy
     *
     * @uses \Dkarlovi\Dockerfile\Stage::__construct
     * @uses \Dkarlovi\Dockerfile\Stage::<protected>
     */
    public function testCanAmendFirstStatement(): void
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

        /** @var \PHPUnit_Framework_MockObject_MockObject|Amendment $statement */
        $statement = $this
            ->getMockBuilder(Amendment::class)
            ->setMethods(['isApplicableTo', 'amendBy'])
            ->getMockForAbstractClass();
        $statement
            ->expects(static::once())
            ->method('isApplicableTo')
            ->with($amendment)
            ->willReturn(true);
        $statement
            ->expects(static::once())
            ->method('amendBy')
            ->with($amendment);

        $stage = new Stage($from, [$statement]);
        $stage->amendFirstBy($amendment);
    }
}
