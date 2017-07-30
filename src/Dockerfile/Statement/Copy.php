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
 * Class Copy.
 */
class Copy implements Statement
{
    /**
     * @var string|string[]
     */
    private $source;

    /**
     * @var string
     */
    private $target;

    /**
     * @var null|string
     */
    private $from;

    /**
     * Copy constructor.
     *
     * @param string|string[] $source
     * @param string          $target
     * @param null|string     $from
     */
    public function __construct($source, string $target, ?string $from = null)
    {
        $this->source = $source;
        $this->target = $target;
        $this->from = $from;
    }

    /**
     * @return string
     */
    public function dump(): string
    {
        $source = $this->source;
        $target = $this->target;
        if (true === \is_array($source)) {
            $source = \implode(' ', $source);
            $target = \rtrim($target, '/').'/';
        }
        $from = $this->from ? ' --from='.$this->from : null;

        return \sprintf('COPY%3$s %1$s %2$s', $source, $target, $from);
    }

    /**
     * @param array $spec
     *
     * @return Copy
     */
    public static function build(array $spec): self
    {
        Assert::keyExists($spec, 'source', 'Copy requires a "source" property');
        Assert::keyExists($spec, 'target', 'Copy requires a "target" property');
        $from = $spec['from'] ?? null;

        return new self($spec['source'], $spec['target'], $from);
    }
}
