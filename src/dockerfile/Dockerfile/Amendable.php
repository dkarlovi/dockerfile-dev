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

namespace Dkarlovi\Dockerfile;

use Dkarlovi\Dockerfile\Exception\InvalidArgumentException;

/**
 * Interface Amendable.
 */
interface Amendable
{
    /**
     * @param Amendment $amendment
     *
     * @return bool
     */
    public function isAmendableWith(Amendment $amendment): bool;

    /**
     * @param Amendment $amendment
     *
     * @throws InvalidArgumentException if not amendable
     */
    public function amendWith(Amendment $amendment): void;
}
