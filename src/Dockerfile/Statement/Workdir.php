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
use Webmozart\Assert\Assert;

/**
 * Class Workdir.
 */
class Workdir implements Statement
{
    /**
     * @var string
     */
    private $dir;

    /**
     * Workdir constructor.
     *
     * @param string $dir
     */
    public function __construct(string $dir)
    {
        $this->dir = $dir;
    }

    /**
     * @return string
     */
    public function dump(): string
    {
        return \sprintf('WORKDIR %1$s', $this->dir);
    }

    /**
     * @param array $spec
     *
     * @return Workdir
     */
    public static function build(array $spec): self
    {
        Assert::keyExists($spec, 'dir', 'Workdir requires a "dir" property');

        return new self($spec['dir']);
    }
}
