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
 * Interface Amendment.
 */
interface Amendment
{
    /**
     * @param Amendment $amendment
     *
     * @return bool
     */
    public function isApplicableTo(Amendment $amendment): bool;

    /**
     * @param Amendment $amendment
     */
    public function amendBy(Amendment $amendment): void;

    /**
     * @return string
     */
    public function getIntent(): string;

    /**
     * @return mixed
     */
    public function getAmendmentBody();
}
