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

namespace Dkarlovi\Dockerfile\Statement\Run;

use Dkarlovi\Dockerfile\Dumpable;

/**
 * Class Command.
 */
class Command implements Dumpable
{
    /**
     * @var string
     */
    private $intention;

    /**
     * @var null|string[]
     */
    private $params;

    /**
     * Command constructor.
     *
     * @param string        $intention
     * @param string[]|null $params
     */
    public function __construct(string $intention, ?array $params = null)
    {
        $this->intention = $intention;
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

        return \sprintf($template, $this->intention, $params);
    }
}
