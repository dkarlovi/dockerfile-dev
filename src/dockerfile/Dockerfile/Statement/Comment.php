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
 * Class Comment.
 */
class Comment implements Statement
{
    /**
     * @var string
     */
    private $content;

    /**
     * @param $content
     */
    public function __construct(string $content)
    {
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function dump(): string
    {
        return '# '.\str_replace("\n", "\n# ", $this->content);
    }

    /**
     * @param array $spec
     *
     * @return Comment
     */
    public static function build(array $spec): self
    {
        Assert::keyExists($spec, 'content', 'Comment requires a "content" property');

        return new self($spec['content']);
    }
}
