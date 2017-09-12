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

namespace Dkarlovi\Dockerfile\Test\Statement;

use Dkarlovi\Dockerfile\Statement\Comment;
use PHPUnit\Framework\TestCase;

/**
 * Class CommentTest.
 */
class CommentTest extends TestCase
{
    /**
     * @dataProvider getConstructFixtures
     * @covers       \Dkarlovi\Dockerfile\Statement\Comment::__construct
     * @covers       \Dkarlovi\Dockerfile\Statement\Comment::dump
     *
     * @param string $content
     * @param string $fixture
     */
    public function testCanConstructAStatement(string $content, string $fixture): void
    {
        $comment = new Comment($content);

        static::assertEquals($fixture, $comment->dump());
    }

    /**
     * @dataProvider getBuildFixtures
     * @covers       \Dkarlovi\Dockerfile\Statement\Comment::build
     * @covers       \Dkarlovi\Dockerfile\Statement\Comment::dump
     *
     * @uses         \Dkarlovi\Dockerfile\Statement\Comment::__construct
     *
     * @param array  $spec
     * @param string $fixture
     */
    public function testCanBuildAStatement(array $spec, string $fixture): void
    {
        $comment = Comment::build($spec);

        static::assertEquals($fixture, $comment->dump());
    }

    /**
     * @covers \Dkarlovi\Dockerfile\Statement\Comment::build
     */
    public function testWhenBuildingAStatementTheContentPropertyIsRequired(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Comment requires a "content" property');

        Comment::build([]);
    }

    /**
     * @covers \Dkarlovi\Dockerfile\Statement\Comment::getIntent
     *
     * @uses   \Dkarlovi\Dockerfile\Statement\Comment::__construct
     */
    public function testStatementIntentIsContentUntilFirstNewline(): void
    {
        $comment1 = new Comment('foo bar');
        static::assertEquals('foo bar', $comment1->getIntent());

        $comment2 = new Comment("foo\nbar");
        static::assertEquals('foo', $comment2->getIntent());
    }

    /**
     * @covers \Dkarlovi\Dockerfile\Statement\Comment::getAmendmentBody
     *
     * @uses   \Dkarlovi\Dockerfile\Statement\Comment::__construct
     */
    public function testAmendmentBodyIsContentAfterFirstNewline(): void
    {
        $comment1 = new Comment('foo bar');
        static::assertEquals('foo bar', $comment1->getAmendmentBody());

        $comment2 = new Comment("foo\nbar");
        static::assertEquals('bar', $comment2->getAmendmentBody());
    }

    /**
     * @covers \Dkarlovi\Dockerfile\Statement\Comment::isApplicableTo
     *
     * @uses   \Dkarlovi\Dockerfile\Statement\Comment::__construct
     * @uses   \Dkarlovi\Dockerfile\Statement\Comment::getIntent
     */
    public function testAmendmentIsApplicableIfIntentIsInContent(): void
    {
        $statement = new Comment('foo');
        $amendment1 = new Comment("foo\nbar");
        static::assertTrue($statement->isApplicableTo($amendment1));

        $amendment2 = new Comment("bar\nfoo");
        static::assertFalse($statement->isApplicableTo($amendment2));
    }

    /**
     * @covers \Dkarlovi\Dockerfile\Statement\Comment::amendBy
     * @covers \Dkarlovi\Dockerfile\Statement\Comment::<protected>
     *
     * @uses   \Dkarlovi\Dockerfile\Statement\Comment::__construct
     * @uses   \Dkarlovi\Dockerfile\Statement\Comment::dump
     * @uses   \Dkarlovi\Dockerfile\Statement\Comment::getAmendmentBody
     * @uses   \Dkarlovi\Dockerfile\Statement\Comment::getIntent
     * @uses   \Dkarlovi\Dockerfile\Statement\Comment::isApplicableTo
     */
    public function testCanAmendStatementByAmendment(): void
    {
        $statement = new Comment('foo');
        $amendment1 = new Comment("foo\nbar");
        $amendment2 = new Comment("foo\nbat");
        $statement->amendBy($amendment1);
        $statement->amendBy($amendment2);

        static::assertEquals("# foo\n# bar\n# bat", $statement->dump());
    }

    /**
     * @return array
     */
    public function getConstructFixtures(): array
    {
        return [
            ['foo', '# foo'],
            ["foo\nbar", "# foo\n# bar"],
        ];
    }

    /**
     * @return array
     */
    public function getBuildFixtures(): array
    {
        return [
            [['content' => 'foo'], '# foo'],
            [['content' => "foo\nbar"], "# foo\n# bar"],
        ];
    }
}
