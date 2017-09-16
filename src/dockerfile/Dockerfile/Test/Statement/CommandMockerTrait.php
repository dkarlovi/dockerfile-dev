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

/**
 * Class CommandMockerTrait.
 */
trait CommandMockerTrait
{
    /** @noinspection ReturnTypeCanBeDeclaredInspection */

    /**
     * @return \PHPUnit_Framework_MockObject_Matcher_InvokedCount
     */
    abstract public static function once();

    /** @noinspection ReturnTypeCanBeDeclaredInspection */

    /**
     * @param string $className
     *
     * @return \PHPUnit_Framework_MockObject_MockBuilder
     */
    abstract public function getMockBuilder($className);

    /**
     * @param null|string[] $methods
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|Command
     */
    protected function mockCommand(?array $methods = null): Command
    {
        $command = $this
            ->getMockBuilder(Command::class)
            ->getMock();
        if (null !== $methods) {
            if (true === \array_key_exists('dump', $methods)) {
                $command
                    ->expects(static::any())
                    ->method('dump')
                    ->willReturn($methods['dump']);
            }
            if (true === \array_key_exists('schema', $methods)) {
                $command
                    ->expects(static::any())
                    ->method('dumpSchema')
                    ->willReturn($methods['schema']);
            }
        }

        return $command;
    }
}
