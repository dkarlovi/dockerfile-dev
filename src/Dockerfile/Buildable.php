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

namespace Dkarlovi\Dockerfile;

/**
 * Interface Buildable.
 */
interface Buildable
{
    /** @noinspection ReturnTypeCanBeDeclaredInspection */

    /**
     * @param array $spec
     *
     * @return Buildable
     */
    public static function build(array $spec);
}
