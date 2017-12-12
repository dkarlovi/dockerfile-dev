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

namespace Dkarlovi\Dockerfile\Statement;

use Dkarlovi\Dockerfile\Amendable\AmendableTrait;
use Dkarlovi\Dockerfile\Amendment;
use Dkarlovi\Dockerfile\Statement;
use Webmozart\Assert\Assert;

/**
 * Class Workdir.
 */
class Workdir implements Statement
{
    use AmendableTrait;

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

    /**
     * @return string
     */
    public function getIntent(): string
    {
        return self::class;
    }

    /**
     * @return mixed
     */
    public function getAmendmentBody()
    {
        return $this->dir;
    }

    /**
     * @param Amendment $amendment
     */
    protected function amendSelfWith(Amendment $amendment): void
    {
        $this->dir = $amendment->getAmendmentBody();
    }
}
