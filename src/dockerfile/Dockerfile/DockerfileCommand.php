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

use Dkarlovi\Dockerfile\Amendable\AmendableTrait;
use Webmozart\Assert\Assert;

/**
 * Class Command.
 */
class DockerfileCommand implements Command
{
    use AmendableTrait;

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
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * @return DockerfileCommand
     */
    public static function build(array $spec): DockerfileCommand
    {
        Assert::keyExists($spec, 'intent', 'Command requires an "intent" property');

        $params = $spec['params'] ?? null;

        return new self($spec['intent'], $params);
    }

    /**
     * @return string
     */
    public function getIntent(): string
    {
        return $this->intent;
    }

    /**
     * @return mixed
     */
    public function getAmendmentBody()
    {
        return $this->params;
    }

    /**
     * @param Amendment $amendment
     */
    protected function amendSelfWith(Amendment $amendment): void
    {
        $this->params = \array_merge((array) $this->params, $amendment->getIntent());
    }
}
