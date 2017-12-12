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

namespace Dkarlovi\Dockerfile\Builder\Test;

use Dkarlovi\Dockerfile\Builder\Builder;
use PHPUnit\Framework\TestCase;

/**
 * Class BuilderTest.
 *
 * @coversNothing
 */
class BuilderTest extends TestCase
{
    /** @noinspection PhpMethodNamingConventionInspection */

    /**
     * @dataProvider getFixtures
     *
     * @param string $fixture
     * @param string $yamlSpec
     */
    public function testBuiltDockerfilesShouldMatchFixtures(string $fixture, string $yamlSpec): void
    {
        $fixture = Fixture::getDockerfilePath($fixture);
        $dockerfile = Builder::build(Fixture::getSpecPath($yamlSpec));

        static::assertStringEqualsFile($fixture, $dockerfile->dump());
    }

    /**
     * @return string[][]
     */
    public function getFixtures(): array
    {
        return [
            // Dockerfile spec file, YAML spec file
            ['alpine', 'alpine'],
            ['multistage', 'multistage'],
            ['symfony', 'symfony'],
        ];
    }
}
