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
     * @return array
     */
    public function getConstructFixtures(): array
    {
        return [
            ['foo', '# foo'],
            ["foo\nbar", "# foo\n# bar"],
        ];
    }
}
