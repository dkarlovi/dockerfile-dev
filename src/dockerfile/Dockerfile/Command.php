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

use Webmozart\Assert\Assert;

/**
 * Class Command.
 */
class Command implements Buildable, Dumpable
{
    /**
     * @var string
     */
    private $intent;

    /**
     * @var null|string[]
     */
    private $params;

    /**
     * Command constructor.
     *
     * @param string        $intent
     * @param string[]|null $params
     */
    public function __construct(string $intent, ?array $params = null)
    {
        $this->intent = $intent;
        $this->params = $params;
    }

    /**
     * @return string
     */
    public function dump(): string
    {
        $template = '%1$s';
        $params = null;
        if (null !== $this->params) {
            $template .= ' %2$s';
            $params = \implode(' ', $this->params);
        }

        return \sprintf($template, $this->intent, $params);
    }

    /**
     * @return string
     */
    public function dumpSchema(): string
    {
        $schema = [$this->intent];
        if (null !== $this->params) {
            $schema = \array_merge($schema, $this->params);
        }

        return \sprintf('["%1$s"]', \implode('", "', $schema));
    }

    /**
     * @param array $spec
     *
     * @return Command
     */
    public static function build(array $spec): Command
    {
        Assert::keyExists($spec, 'intent', 'Command requires an "intent" property');

        $params = $spec['params'] ?? null;

        return new self($spec['intent'], $params);
    }
}
