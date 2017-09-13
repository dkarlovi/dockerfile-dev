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
use Dkarlovi\Dockerfile\DockerfileCommand;
use Dkarlovi\Dockerfile\Stage;
use Dkarlovi\Dockerfile\Statement\Comment;
use Dkarlovi\Dockerfile\Statement\Copy;
use Dkarlovi\Dockerfile\Statement\Entrypoint;
use Dkarlovi\Dockerfile\Statement\Env;
use Dkarlovi\Dockerfile\Statement\From;
use Dkarlovi\Dockerfile\Statement\Healthcheck;
use Dkarlovi\Dockerfile\Statement\Run;
use Dkarlovi\Dockerfile\Statement\Workdir;
use PHPUnit\Framework\TestCase;

/**
 * Class DockerfileTest.
 *
 * @coversNothing
 */
class DockerfileTest extends TestCase
{
    /**
     * @covers       \Dkarlovi\Dockerfile\Dockerfile::dump()
     * @dataProvider getFixtures
     *
     * @param string $fixture
     * @param array  $stages
     */
    public function testBuiltDockerfilesShouldMatchFixtures(string $fixture, array $stages): void
    {
        $fixture = Fixture::getDockerfilePath($fixture);
        $dockerfile = new Dockerfile($stages);

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
                    new Stage(
                        new From('alpine:latest'),
                        [
                            new Copy('test', '/abc/test'),
                            new Copy(['test1', 'test2'], '/abc'),
                            new Run(
                                [
                                    new DockerfileCommand(
                                        'apk add',
                                        [
                                            '--no-cache',
                                            'php7',
                                            'php7-redis',
                                        ]
                                    ),
                                    new DockerfileCommand('date'),
                                ]
                            ),
                        ]
                    ),
                ],
            ],
            [
                'multistage',
                [
                    new Stage(
                        new From('alpine:latest'),
                        [new Copy('test', '/abc/test')],
                        'builder'
                    ),
                    new Stage(
                        new From('alpine:3.6'),
                        [new Copy('/abc/test', '/bcd/test', 'builder')]
                    ),
                ],
            ],
            [
                'symfony',
                [
                    new Stage(
                        new From('php:7.1-fpm-alpine'),
                        [
                            new Env('REDIS_VERSION', '3.1.2'),
                            new Comment(
                                "required modules:\nampq apcu igbinary intl opcache pcntl pdo_mysql redis xdebug"
                            ),
                            new Run(
                                [
                                    new DockerfileCommand(
                                        'apk add',
                                        [
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
                                        ]
                                    ),
                                    new DockerfileCommand(
                                        'docker-php-ext-install',
                                        ['intl', 'opcache', 'pcntl', 'pdo_mysql']
                                    ),
                                    new DockerfileCommand(
                                        'pecl install',
                                        ['apcu', 'igbinary', 'xdebug']
                                    ),
                                    new DockerfileCommand(
                                        'docker-php-ext-enable',
                                        ['apcu', 'igbinary', 'xdebug']
                                    ),
                                    new DockerfileCommand(
                                        'apk del',
                                        ['autoconf', 'g++', 'icu-dev', 'make', 'cmake', 'openssl-dev']
                                    ),
                                    new DockerfileCommand(
                                        'rm -rf',
                                        [
                                            '/tmp/pear',
                                            '/usr/src',
                                            '/usr/local/include/php',
                                            '/usr/include',
                                            '/var/cache/*',
                                        ]
                                    ),
                                ]
                            ),
                            new Comment('order is important here'),
                            new Copy('vendor', '/app/vendor'),
                            new Copy('web', '/app/web'),
                            new Copy('var', '/app/var'),
                            new Copy('src', '/app/src'),
                            new Copy('etc', '/app/etc'),
                            new Run(
                                [
                                    new DockerfileCommand('chown -R www-data', ['/app/var']),
                                ]
                            ),
                            new Copy(
                                ['./.infra/docker/app/php.ini', './.infra/docker/app/ext-*'],
                                '/usr/local/etc/php/conf.d'
                            ),
                            new Copy(
                                ['./.infra/docker/app/entrypoint.sh', './.infra/docker/app/docker-healthcheck'],
                                '/usr/local/bin'
                            ),
                            new Healthcheck(new DockerfileCommand('docker-healthcheck')),
                            new Entrypoint(
                                new DockerfileCommand(
                                    '/sbin/tini',
                                    [
                                        '--',
                                        '/usr/local/bin/entrypoint.sh',
                                        '/usr/local/sbin/php-fpm',
                                        '--nodaemonize',
                                    ]
                                )
                            ),
                            new Workdir('/app'),
                        ]
                    ),
                ],
            ],
        ];
    }
}
