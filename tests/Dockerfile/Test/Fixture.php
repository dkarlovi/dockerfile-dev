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

/**
 * Class Fixture.
 */
class Fixture
{
    /**
     * @param $type
     *
     * @return string
     */
    public static function getDockerfilePath($type): string
    {
        $root = __DIR__.'/../../fixtures';
        $path = \sprintf('%1$s/%2$s/Dockerfile', $root, $type);
        if (false === \file_exists($path)) {
            throw new \InvalidArgumentException('No such fixture');
        }

        return $path;
    }
}
