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
        $collection = $this->getAmendableCollection();

        $this->amend($amendment, $collection);
    }

    /**
     * @param Amendment $amendment
     *
     * @throws Exception\InvalidArgumentException
     */
    public function amendLastBy(Amendment $amendment): void
    {
        $collection = $this->getAmendableCollection();
        \rsort($collection);

        $this->amend($amendment, $collection);
    }

    /**
     * @return Amendment[]
     */
    abstract protected function getAmendableCollection(): array;

    /**
     * @param Amendment   $amendment
     * @param Amendment[] $collection
     */
    private function amend(Amendment $amendment, $collection): void
    {
        foreach ($collection as $amendable) {
            if (true === $amendable->isApplicableTo($amendment)) {
                $amendable->amendBy($amendment);

                return;
            }
        }

        throw new InvalidArgumentException('No amendable collection item to amend found');
    }
}
