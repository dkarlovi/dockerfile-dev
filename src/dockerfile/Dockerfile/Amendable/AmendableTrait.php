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

namespace Dkarlovi\Dockerfile\Amendable;

use Dkarlovi\Dockerfile\Amendment;
use Dkarlovi\Dockerfile\Exception\InvalidArgumentException;

/**
 * Trait AmendableTrait.
 */
trait AmendableTrait
{
    /**
     * @return string
     */
    abstract public function getIntent(): string;

    /**
     * @param Amendment $amendment
     *
     * @return bool
     */
    public function isAmendableWith(Amendment $amendment): bool
    {
        return $amendment instanceof static
            && true === $this->isSelfAmendableWith($amendment);
    }

    /**
     * @param Amendment $amendment
     *
     * @throws InvalidArgumentException
     */
    public function amendWith(Amendment $amendment): void
    {
        if (false === $this->isAmendableWith($amendment)) {
            throw new InvalidArgumentException('Amendment not applicable here');
        }

        $this->amendSelfWith($amendment);
    }

    /**
     * @param Amendment $amendment
     */
    abstract protected function amendSelfWith(Amendment $amendment): void;

    /**
     * @param Amendment $amendment
     *
     * @return bool
     */
    protected function isSelfAmendableWith(Amendment $amendment): bool
    {
        return $amendment->getIntent() === $this->getIntent();
    }
}
