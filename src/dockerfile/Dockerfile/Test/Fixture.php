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
        return self::getAssetPath(__DIR__.'/fixtures/dockerfiles', $type, 'No such fixture');
    }

    /**
     * @param string $type
     *
     * @throws \InvalidArgumentException
     *
     * @return string
     */
    public static function getSpecPath(string $type): string
    {
        return self::getAssetPath(__DIR__.'/fixtures/specs', $type, 'No such spec');
    }

    /**
     * @param string $root
     * @param string $type
     *
     * @return string
     */
    private static function formatPath(string $root, string $type): string
    {
        return  \sprintf('%1$s/%2$s/Dockerfile', $root, $type);
    }

    /**
     * @param string $root
     * @param string $type
     * @param string $errorMessage
     *
     * @throws \InvalidArgumentException
     *
     * @return string
     */
    private static function getAssetPath(string $root, string $type, string $errorMessage): string
    {
        $path = self::formatPath($root, $type);
        if (false === \file_exists($path)) {
            throw new \InvalidArgumentException($errorMessage);
        }

        return $path;
    }
}
