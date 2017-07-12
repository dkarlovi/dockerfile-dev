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

use Dkarlovi\Dockerfile\Dockerfile;
use Dkarlovi\Dockerfile\Stage;
use Dkarlovi\Dockerfile\Statement\Copy;
use Dkarlovi\Dockerfile\Statement\From;
use Dkarlovi\Dockerfile\Statement\Run;
use PHPUnit\Framework\TestCase;

/**
 * Class DockerfileTest.
 *
 * @coversNothing
 */
class DockerfileTest extends TestCase
{
    /**
     * @covers \Dkarlovi\Dockerfile\Dockerfile::dump()
     */
    public function testItShouldAlwaysHaveAFromStatement(): void
    {
        $fixture = Fixture::getDockerfilePath('alpine');
        $dockerfile = new Dockerfile(
            [
                new Stage(new From('alpine'), [
                    new Copy('test', '/abc/test'),
                    new Copy(['test1', 'test2'], '/abc'),
                    new Run([
                        new Run\Command('apk add', [
                            '--no-cache',
                            'php7',
                            'php7-redis',
                        ]),
                        new Run\Command('date'),
                    ]),
                ]),
            ]
        );
        static::assertStringEqualsFile($fixture, $dockerfile->dump());
    }

    /**
     * @covers \Dkarlovi\Dockerfile\Dockerfile::dump()
     */
    public function testItShouldSupportMultiStageDockerfile(): void
    {
        $stages = [
            new Stage(new From('alpine', 'latest'), [
                new Copy('test', '/abc/test'),
            ], 'builder'),
            new Stage(new From('alpine', '3.6'), [
                new Copy('/abc/test', '/bcd/test', 'builder'),
            ]),
        ];
        $fixture = Fixture::getDockerfilePath('multistage');
        $dockerfile = new Dockerfile($stages);

        static::assertStringEqualsFile($fixture, $dockerfile->dump());
    }
}
