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
            ['alpine', [
                [
                    'from' => ['alpine', 'latest'],
                    'statements' => [
                        ['type' => 'copy', 'params' => ['from' => 'test', 'to' => '/abc/test']],
                        ['type' => 'copy', 'params' => ['from' => ['test1', 'test2'], 'to' => '/abc']],
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
            ]],
        ];
    }
}
