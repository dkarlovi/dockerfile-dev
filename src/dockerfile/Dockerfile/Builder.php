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
 * Class Builder.
 */
class Builder
{
    /**
     * @var array
     */
    private $spec;

    /**
     * @param array $spec
     */
    public function __construct(array $spec)
    {
        $this->spec = $spec;
    }

    /**
     * @return Dockerfile
     */
    public function build(): Dockerfile
    {
        return Dockerfile::build($this->spec);
    }
}
