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
     * @param string $type
     *
     * @throws \InvalidArgumentException
     *
     * @return string
     */
    public static function getDockerfilePath(string $type): string
    {
        return self::getAssetPath(__DIR__.'/fixtures/dockerfiles', $type, 'Dockerfile', 'No such fixture');
    }

    /**
     * @param string $root
     * @param string $type
     * @param string $filename
     * @param string $errorMessage
     *
     * @return string
     */
    protected static function getAssetPath(string $root, string $type, string $filename, string $errorMessage): string
    {
        $path = self::formatPath($root, $type, $filename);
        if (false === \file_exists($path)) {
            $error = \sprintf('%1$s: %2$s', $path, $errorMessage);

            throw new \InvalidArgumentException($error);
        }

        return $path;
    }

    /**
     * @param string $root
     * @param string $type
     * @param string $filename
     *
     * @return string
     */
    private static function formatPath(string $root, string $type, string $filename): string
    {
        return \sprintf('%1$s/%2$s/%3$s', $root, $type, $filename);
    }
}
