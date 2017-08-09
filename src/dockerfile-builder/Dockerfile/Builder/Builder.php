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

namespace Dkarlovi\Dockerfile\Builder;

use Dkarlovi\Dockerfile\Builder as SpecBuilder;
use Dkarlovi\Dockerfile\Dockerfile;
use Symfony\Component\Yaml\Yaml;
use Webmozart\Assert\Assert;

/**
 * Class Builder.
 */
class Builder
{
    /**
     * @param string $path
     *
     * @throws \Symfony\Component\Yaml\Exception\ParseException
     * @throws \InvalidArgumentException
     *
     * @return Dockerfile
     */
    public static function build(string $path): Dockerfile
    {
        if (false === \file_exists($path)) {
            throw new \InvalidArgumentException(\sprintf('Cannot read "%1$s", no such file', $path));
        }
        if (false === \is_file($path)) {
            throw new \InvalidArgumentException(\sprintf('Cannot read "%1$s", not a file', $path));
        }
        if (false === \is_readable($path)) {
            throw new \InvalidArgumentException(\sprintf('Cannot read "%1$s", permission denied', $path));
        }
        $spec = Yaml::parse(\file_get_contents($path));
        Assert::isArray($spec);
        $builder = new SpecBuilder($spec);

        return $builder->build();
    }
}
