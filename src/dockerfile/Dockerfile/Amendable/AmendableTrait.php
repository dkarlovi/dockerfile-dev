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
    public function isApplicableTo(Amendment $amendment): bool
    {
        return $amendment instanceof static
            && $amendment->getIntent() === $this->getIntent();
    }

    /**
     * @param Amendment $amendment
     *
     * @throws InvalidArgumentException
     */
    public function amendBy(Amendment $amendment): void
    {
        if (false === $this->isApplicableTo($amendment)) {
            throw new InvalidArgumentException('Amendment not applicable here');
        }

        $this->amendSelfBy($amendment);
    }

    /**
     * @param Amendment $amendment
     */
    abstract protected function amendSelfBy(Amendment $amendment): void;
}
