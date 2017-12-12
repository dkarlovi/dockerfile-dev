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
 * Class Env.
 */
class Env implements Statement
{
    use AmendableTrait;

    /**
     * @var string
     */
    private $name;

    /**
     * @var float|int|string
     */
    private $value;

    /**
     * @param string           $name
     * @param string|int|float $value
     */
    public function __construct(string $name, $value)
    {
        $this->name = $name;
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function dump(): string
    {
        return \sprintf('ENV %1$s %2$s', $this->name, $this->value);
    }

    /**
     * @param array $spec
     *
     * @return Env
     */
    public static function build(array $spec): self
    {
        Assert::keyExists($spec, 'name', 'Env requires a "name" property');
        Assert::keyExists($spec, 'value', 'Env requires a "value" property');

        return new self($spec['name'], $spec['value']);
    }

    /**
     * @return string
     */
    public function getIntent(): string
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getAmendmentBody()
    {
        return $this->value;
    }

    /**
     * @param Amendment $amendment
     */
    protected function amendSelfWith(Amendment $amendment): void
    {
        $this->value = $amendment->getAmendmentBody();
    }
}
