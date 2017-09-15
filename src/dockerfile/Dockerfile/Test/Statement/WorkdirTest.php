<?php

declare(strict_types = 1);

namespace Dkarlovi\Dockerfile\Test\Statement;

use Dkarlovi\Dockerfile\Statement\Workdir;
use PHPUnit\Framework\TestCase;

/**
 * Class WorkdirTest
 */
class WorkdirTest extends TestCase
{
    /**
     * @dataProvider getConstructFixtures
     * @covers       \Dkarlovi\Dockerfile\Statement\Workdir::__construct
     * @covers       \Dkarlovi\Dockerfile\Statement\Workdir::dump
     *
     * @param string $dir
     * @param string $fixture
     */
    public function testCanConstructAStatement(string $dir, string $fixture): void
    {
        $workdir = new Workdir($dir);

        static::assertEquals($fixture, $workdir->dump());
    }

    /**
     * @dataProvider getBuildFixtures
     * @covers       \Dkarlovi\Dockerfile\Statement\Workdir::build
     * @covers       \Dkarlovi\Dockerfile\Statement\Workdir::dump
     *
     * @uses         \Dkarlovi\Dockerfile\Statement\Workdir::__construct
     *
     * @param array  $spec
     * @param string $fixture
     */
    public function testCanBuildAStatement(array $spec, string $fixture): void
    {
        $workdir = Workdir::build($spec);

        static::assertEquals($fixture, $workdir->dump());
    }

    /**
     * @covers \Dkarlovi\Dockerfile\Statement\Workdir::build
     */
    public function testWhenBuildingAStatementTheDirPropertyIsRequired(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Workdir requires a "dir" property');

        Workdir::build([]);
    }

    /**
     * @covers \Dkarlovi\Dockerfile\Statement\Workdir::getIntent
     *
     * @uses   \Dkarlovi\Dockerfile\Statement\Workdir::__construct
     */
    public function testStatementIntentIsName(): void
    {
        $workdir = new Workdir('/app/bar');

        static::assertEquals(Workdir::class, $workdir->getIntent());
    }

    /**
     * @covers \Dkarlovi\Dockerfile\Statement\Workdir::getAmendmentBody
     *
     * @uses   \Dkarlovi\Dockerfile\Statement\Workdir::__construct
     */
    public function testAmendmentBodyIsDir(): void
    {
        $workdir = new Workdir('/app/bar');
        static::assertEquals('/app/bar', $workdir->getAmendmentBody());
    }

    /**
     * @covers \Dkarlovi\Dockerfile\Statement\Workdir::amendBy
     * @covers \Dkarlovi\Dockerfile\Statement\Workdir::<protected>
     * @covers \Dkarlovi\Dockerfile\Statement\Workdir::isApplicableTo
     *
     * @uses   \Dkarlovi\Dockerfile\Statement\Workdir::__construct
     * @uses   \Dkarlovi\Dockerfile\Statement\Workdir::dump
     * @uses   \Dkarlovi\Dockerfile\Statement\Workdir::getAmendmentBody
     * @uses   \Dkarlovi\Dockerfile\Statement\Workdir::getIntent
     */
    public function testCanAmendStatementByAmendment(): void
    {
        $statement = new Workdir('/app/bar');
        $amendment = new Workdir('/app/bat');
        $statement->amendBy($amendment);

        static::assertEquals('WORKDIR /app/bat', $statement->dump());
    }

    /**
     * @return array
     */
    public function getConstructFixtures(): array
    {
        return [
            ['/app/abc', 'WORKDIR /app/abc'],
        ];
    }

    /**
     * @return array
     */
    public function getBuildFixtures(): array
    {
        return [
            [['dir' => '/app/abc'], 'WORKDIR /app/abc'],
        ];
    }
}
