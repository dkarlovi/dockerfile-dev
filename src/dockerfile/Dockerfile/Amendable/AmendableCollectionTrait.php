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
use Dkarlovi\Dockerfile\Exception;
use Dkarlovi\Dockerfile\Exception\InvalidArgumentException;

/**
 * Trait AmendableCollectionTrait.
 */
trait AmendableCollectionTrait
{
    /**
     * @param Amendment $amendment
     *
     * @throws Exception\InvalidArgumentException
     */
    public function amendFirstBy(Amendment $amendment): void
    {
        $collection = (array) $this->getAmendableCollection();
        foreach ($collection as $amendable) {
            if (true === $amendable->isApplicableTo($amendment)) {
                $amendable->amendBy($amendment);

                return;
            }
        }

        throw new InvalidArgumentException('No amendable collection item to amend first found');
    }

    /**
     * @param Amendment $amendment
     *
     * @throws Exception\InvalidArgumentException
     */
    public function amendLastBy(Amendment $amendment): void
    {
        // TODO: Implement amendLastBy() method.
    }

    /**
     * @return null|Amendment[]
     */
    abstract protected function getAmendableCollection(): array;
}
