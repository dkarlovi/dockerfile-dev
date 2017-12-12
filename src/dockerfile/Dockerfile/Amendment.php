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

/**
 * Interface Amendment.
 */
interface Amendment
{
    /**
     * @return string
     */
    public function getIntent(): string;

    /**
     * @return mixed
     */
    public function getAmendmentBody();
}
