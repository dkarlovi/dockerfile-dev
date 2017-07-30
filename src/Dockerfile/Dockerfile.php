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
 * Class Dockerfile.
 */
class Dockerfile implements Dumpable, Buildable
{
    /**
     * @var Stage[]
     */
    private $stages;

    /**
     * @param Stage[] $stages
     */
    public function __construct(array $stages)
    {
        $this->stages = $stages;
    }

    /**
     * @return string
     */
    public function dump(): string
    {
        $out = [];
        foreach ($this->stages as $stage) {
            $out[] = $stage->dump();
        }

        return \implode("\n", $out);
    }

    /**
     * @param array $spec
     *
     * @return Dockerfile
     */
    public static function build(array $spec): Dockerfile
    {
        $stages = [];
        foreach ($spec as $alias => $stage) {
            $stages[] = Stage::build($stage);
        }

        return new self($stages);
    }
}
