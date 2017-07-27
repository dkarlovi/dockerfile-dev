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

namespace Dkarlovi\Dockerfile\Statement;

use Dkarlovi\Dockerfile\Statement;

/**
 * Class From.
 */
class From implements Statement
{
    /**
     * @var string
     */
    private $image;

    /**
     * @var null|string|int|float
     */
    private $tag;

    /**
     * @param string                $image
     * @param null|string|int|float $tag
     */
    public function __construct(string $image, $tag = null)
    {
        $this->image = $image;
        $this->tag = $tag ?? 'latest';
    }

    /**
     * @return string
     */
    public function dump(): string
    {
        return \sprintf('FROM %1$s:%2$s', $this->image, $this->tag);
    }

    /**
     * @param array $spec
     *
     * @return From
     */
    public static function build(array $spec): self
    {
        // TODO: Implement build() method.
    }
}
