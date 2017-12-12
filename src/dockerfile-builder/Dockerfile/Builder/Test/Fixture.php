<?php

declare(strict_types = 1);

/*
 * This file is part of Dockerfile.
 *
 * (c) Dalibor Karlović <dalibor@flexolabs.io>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Dkarlovi\Dockerfile\Builder\Test;

use Dkarlovi\Dockerfile\Test\Fixture as BaseFixture;

/**
 * Class Fixture.
 */
class Fixture extends BaseFixture
{
    /**
     * @param string $type
     *
     * @throws \InvalidArgumentException
     *
     * @return string
     */
    public static function getSpecPath(string $type): string
    {
        return self::getAssetPath(__DIR__.'/fixtures/specs', $type, 'spec.yaml', 'No such specs');
    }
}
