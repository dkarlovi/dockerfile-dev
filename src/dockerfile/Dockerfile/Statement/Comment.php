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

namespace Dkarlovi\Dockerfile\Statement;

use Dkarlovi\Dockerfile\Amendable\AmendableTrait;
use Dkarlovi\Dockerfile\Amendment;
use Dkarlovi\Dockerfile\Statement;
use Webmozart\Assert\Assert;

/**
 * Class Comment.
 */
class Comment implements Statement
{
    use AmendableTrait;

    /**
     * @var string
     */
    private $content;

    /**
     * @param string $content
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

    /**
     * @return string
     */
    public function getIntent(): string
    {
        if (false === ($idx = \mb_strpos($this->content, "\n"))) {
            return $this->content;
        }

        return \mb_substr($this->content, 0, $idx);
    }

    /**
     * @return mixed
     */
    public function getAmendmentBody()
    {
        if (false === ($idx = \mb_strpos($this->content, "\n"))) {
            return $this->content;
        }

        return \mb_substr($this->content, $idx + 1);
    }

    /**
     * @param Amendment $amendment
     *
     * @return bool
     */
    protected function isSelfAmendableWith(Amendment $amendment): bool
    {
        return false !== \mb_strpos($this->content, $amendment->getIntent());
    }

    /**
     * @param Amendment $amendment
     */
    protected function amendSelfWith(Amendment $amendment): void
    {
        $this->content .= "\n".$amendment->getAmendmentBody();
    }
}
