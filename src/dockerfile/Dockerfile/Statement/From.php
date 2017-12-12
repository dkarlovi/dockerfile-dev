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
 * Class From.
 */
class From implements Statement
{
    use AmendableTrait;

    /**
     * @var string
     */
    private $image;

    /**
     * @param string $image
     */
    public function __construct(string $image)
    {
        $this->image = $image;
    }

    /**
     * @return string
     */
    public function dump(): string
    {
        return \sprintf('FROM %1$s', $this->image);
    }

    /**
     * @param array $spec
     *
     * @return From
     */
    public static function build(array $spec): self
    {
        Assert::keyExists($spec, 'image', 'From requires an "image" property');

        return new self($spec['image']);
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
        return $this->image;
    }

    /**
     * @param Amendment $amendment
     */
    protected function amendSelfWith(Amendment $amendment): void
    {
        $this->image = $amendment->getAmendmentBody();
    }
}
