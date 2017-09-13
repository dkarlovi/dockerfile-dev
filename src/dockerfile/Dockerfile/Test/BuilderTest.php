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
     * @covers       \Dkarlovi\Dockerfile\DockerfileCommand::build()
     * @covers       \Dkarlovi\Dockerfile\Dockerfile::build()
     * @covers       \Dkarlovi\Dockerfile\Stage::build()
     * @covers       \Dkarlovi\Dockerfile\StatementFactory::build()
     *
     * @uses         \Dkarlovi\Dockerfile\Statement\Comment::build()
     * @uses         \Dkarlovi\Dockerfile\Statement\Copy::build()
     * @uses         \Dkarlovi\Dockerfile\Statement\Entrypoint::build()
     * @uses         \Dkarlovi\Dockerfile\Statement\Env::build()
     * @uses         \Dkarlovi\Dockerfile\Statement\From::build()
     * @uses         \Dkarlovi\Dockerfile\Statement\Healthcheck::build()
     * @uses         \Dkarlovi\Dockerfile\Statement\Run::build()
     * @uses         \Dkarlovi\Dockerfile\Statement\Workdir::build()
     * @uses         \Dkarlovi\Dockerfile\Builder::__construct
     * @uses         \Dkarlovi\Dockerfile\Dockerfile::__construct
     * @uses         \Dkarlovi\Dockerfile\Dockerfile::dump
     * @uses         \Dkarlovi\Dockerfile\Stage::__construct
     * @uses         \Dkarlovi\Dockerfile\Stage::dump
     * @uses         \Dkarlovi\Dockerfile\Stage::<private>
     * @uses         \Dkarlovi\Dockerfile\DockerfileCommand::__construct
     * @uses         \Dkarlovi\Dockerfile\DockerfileCommand::dump
     * @uses         \Dkarlovi\Dockerfile\DockerfileCommand::dumpSchema
     * @uses         \Dkarlovi\Dockerfile\Statement\Comment::__construct
     * @uses         \Dkarlovi\Dockerfile\Statement\Comment::dump
     * @uses         \Dkarlovi\Dockerfile\Statement\Copy::__construct
     * @uses         \Dkarlovi\Dockerfile\Statement\Copy::dump
     * @uses         \Dkarlovi\Dockerfile\Statement\Entrypoint::__construct
     * @uses         \Dkarlovi\Dockerfile\Statement\Entrypoint::dump
     * @uses         \Dkarlovi\Dockerfile\Statement\Env::__construct
     * @uses         \Dkarlovi\Dockerfile\Statement\Env::dump
     * @uses         \Dkarlovi\Dockerfile\Statement\From::__construct
     * @uses         \Dkarlovi\Dockerfile\Statement\From::dump
     * @uses         \Dkarlovi\Dockerfile\Statement\Healthcheck::__construct
     * @uses         \Dkarlovi\Dockerfile\Statement\Healthcheck::dump
     * @uses         \Dkarlovi\Dockerfile\Statement\Run::__construct
     * @uses         \Dkarlovi\Dockerfile\Statement\Run::dump
     * @uses         \Dkarlovi\Dockerfile\Statement\Workdir::__construct
     * @uses         \Dkarlovi\Dockerfile\Statement\Workdir::dump
     * @dataProvider getFixtures
     *
     * @param string $fixture
     * @param array  $spec
     */
    public function testBuiltDockerfilesShouldMatchFixtures(string $fixture, array $spec): void
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
                        'from' => ['image' => 'alpine:latest'],
                        'statements' => [
                            ['type' => 'copy', 'params' => ['source' => 'test', 'target' => '/abc/test']],
                            ['type' => 'copy', 'params' => ['source' => ['test1', 'test2'], 'target' => '/abc']],
                            [
                                'type' => 'run',
                                'params' => [
                                    'commands' => [
                                        ['intent' => 'apk add', 'params' => ['--no-cache', 'php7', 'php7-redis']],
                                        ['intent' => 'date'],
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
                        'from' => ['image' => 'alpine:latest'],
                        'statements' => [
                            ['type' => 'copy', 'params' => ['source' => 'test', 'target' => '/abc/test']],
                        ],
                    ],
                    [
                        'from' => ['image' => 'alpine:3.6'],
                        'statements' => [
                            [
                                'type' => 'copy',
                                'params' => ['source' => '/abc/test', 'target' => '/bcd/test', 'from' => 'builder'],
                            ],
                        ],
                    ],
                ],
            ],
            [
                'symfony',
                [
                    [
                        'from' => ['image' => 'php:7.1-fpm-alpine'],
                        'statements' => [
                            [
                                'type' => 'env',
                                'params' => [
                                    'name' => 'REDIS_VERSION',
                                    'value' => '3.1.2',
                                ],
                            ],
                            [
                                'type' => 'comment',
                                'params' => [
                                    'content' => 'required modules:'
                                        ."\n".'ampq apcu igbinary intl opcache pcntl pdo_mysql redis xdebug',
                                ],
                            ],
                            [
                                'type' => 'run',
                                'params' => [
                                    'commands' => [
                                        [
                                            'intent' => 'apk add',
                                            'params' => [
                                                '--update',
                                                '--no-cache',
                                                'fcgi',
                                                'icu',
                                                'inotify-tools',
                                                'tini',
                                                'autoconf',
                                                'cmake',
                                                'g++',
                                                'icu-dev',
                                                'make',
                                                'openssl-dev',
                                            ],
                                        ],
                                        [
                                            'intent' => 'docker-php-ext-install',
                                            'params' => ['intl', 'opcache', 'pcntl', 'pdo_mysql'],
                                        ],
                                        [
                                            'intent' => 'pecl install',
                                            'params' => ['apcu', 'igbinary', 'xdebug'],
                                        ],
                                        [
                                            'intent' => 'docker-php-ext-enable',
                                            'params' => ['apcu', 'igbinary', 'xdebug'],
                                        ],
                                        [
                                            'intent' => 'apk del',
                                            'params' => ['autoconf', 'g++', 'icu-dev', 'make', 'cmake', 'openssl-dev'],
                                        ],
                                        [
                                            'intent' => 'rm -rf',
                                            'params' => [
                                                '/tmp/pear',
                                                '/usr/src',
                                                '/usr/local/include/php',
                                                '/usr/include',
                                                '/var/cache/*',
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                            [
                                'type' => 'comment',
                                'params' => ['content' => 'order is important here'],
                            ],
                            ['type' => 'copy', 'params' => ['source' => 'vendor', 'target' => '/app/vendor']],
                            ['type' => 'copy', 'params' => ['source' => 'web', 'target' => '/app/web']],
                            ['type' => 'copy', 'params' => ['source' => 'var', 'target' => '/app/var']],
                            ['type' => 'copy', 'params' => ['source' => 'src', 'target' => '/app/src']],
                            ['type' => 'copy', 'params' => ['source' => 'etc', 'target' => '/app/etc']],
                            [
                                'type' => 'run',
                                'params' => [
                                    'commands' => [
                                        ['type' => 'run', 'intent' => 'chown -R www-data', 'params' => ['/app/var']],
                                    ],
                                ],
                            ],
                            [
                                'type' => 'copy',
                                'params' => [
                                    'source' => ['./.infra/docker/app/php.ini', './.infra/docker/app/ext-*'],
                                    'target' => '/usr/local/etc/php/conf.d',
                                ],
                            ],
                            [
                                'type' => 'copy',
                                'params' => [
                                    'source' => [
                                        './.infra/docker/app/entrypoint.sh',
                                        './.infra/docker/app/docker-healthcheck',
                                    ],
                                    'target' => '/usr/local/bin',
                                ],
                            ],
                            [
                                'type' => 'healthcheck',
                                'params' => [
                                    'command' => [
                                        'intent' => 'docker-healthcheck',
                                    ],
                                ],
                            ],
                            [
                                'type' => 'entrypoint',
                                'params' => [
                                    'command' => [
                                        'intent' => '/sbin/tini',
                                        'params' => [
                                            '--',
                                            '/usr/local/bin/entrypoint.sh',
                                            '/usr/local/sbin/php-fpm',
                                            '--nodaemonize',
                                        ],
                                    ],
                                ],
                            ],
                            [
                                'type' => 'workdir',
                                'params' => [
                                    'dir' => '/app',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
}
