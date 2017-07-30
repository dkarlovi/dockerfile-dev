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

use Dkarlovi\Dockerfile\Builder;
use PHPUnit\Framework\TestCase;

/**
 * Class BuilderTest.
 */
class BuilderTest extends TestCase
{
    /**
     * @covers       \Dkarlovi\Dockerfile\Builder::build()
     * @dataProvider getFixtures
     *
     * @param string $fixture
     * @param array  $spec
     */
    public function testBuiltDockerfileShouldMatchFixtures(string $fixture, array $spec): void
    {
        $fixture = Fixture::getDockerfilePath($fixture);
        $builder = new Builder($spec);
        $dockerfile = $builder->build();

        static::assertStringEqualsFile($fixture, $dockerfile->dump());
    }

    /**
     * @return array
     */
    public function getFixtures(): array
    {
        return [
            [
                'alpine',
                [
                    [
                        'from' => ['image' => 'alpine', 'tag' => 'latest'],
                        'statements' => [
                            ['type' => 'copy', 'params' => ['source' => 'test', 'target' => '/abc/test']],
                            ['type' => 'copy', 'params' => ['source' => ['test1', 'test2'], 'target' => '/abc']],
                            [
                                'type' => 'run',
                                'params' => [
                                    'commands' => [
                                        ['intention' => 'apk add', 'params' => ['--no-cache', 'php7', 'php7-redis']],
                                        ['intention' => 'date'],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            [
                'multistage',
                [
                    [
                        'alias' => 'builder',
                        'from' => ['image' => 'alpine', 'tag' => 'latest'],
                        'statements' => [
                            ['type' => 'copy', 'params' => ['source' => 'test', 'target' => '/abc/test']],
                        ],
                    ],
                    [
                        'from' => ['image' => 'alpine', 'tag' => 3.6],
                        'statements' => [
                            [
                                'type' => 'copy',
                                'params' => ['source' => '/abc/test', 'target' => '/bcd/test', 'from' => 'builder'],
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
}
