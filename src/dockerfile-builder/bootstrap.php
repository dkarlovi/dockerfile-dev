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

$autoLoaders = [
    // stand-alone auto-loader
    __DIR__.'/vendor/autoload.php',
    // mono-repo auto-loader
    __DIR__.'/../../vendor/autoload.php',
    // project auto-loader
    __DIR__.'/../../autoload.php',
];
foreach ($autoLoaders as $autoLoader) {
    if (true === \file_exists($autoLoader)) {
        /* @noinspection PhpIncludeInspection */
        return include $autoLoader;
    }
}
\fwrite(
    STDERR,
    'You must set up the project dependencies using `composer install`'.PHP_EOL
);
exit(1);
