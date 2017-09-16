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

use Dkarlovi\Dockerfile\Amendable\AmendableCollectionTrait;
use Dkarlovi\Dockerfile\Statement\From;
use Webmozart\Assert\Assert;

/**
 * Class Stage.
 */
class Stage implements AmendableCollection, Dumpable, Buildable
{
    use AmendableCollectionTrait;

    /**
     * @var From
     */
    private $from;

    /**
     * @var null|Statement[]
     */
    private $statements;

    /**
     * @var null|string
     */
    private $alias;

    /**
     * Stage constructor.
     *
     * @param From             $from
     * @param null|Statement[] $statements
     * @param null|string      $alias
     */
    public function __construct(From $from, ?array $statements = null, ?string $alias = null)
    {
        $this->from = $from;
        $this->statements = $statements;
        $this->alias = $alias;
    }

    /**
     * @return string
     */
    public function dump(): string
    {
        $out = [
            $this->header($this->from, $this->alias),
        ];
        if (null !== $this->statements) {
            foreach ($this->statements as $statement) {
                $out[] = $statement->dump();
            }
        }

        return \implode("\n", $out)."\n";
    }

    /**
     * @param string[][] $spec
     *
     * @return Stage
     */
    public static function build(array $spec): self
    {
        Assert::keyExists($spec, 'from', 'Stage requires a "from" statement');
        $from = From::build($spec['from']);

        $statements = null;
        if (true === isset($spec['statements'])) {
            $statements = [];
            /** @var string[] $statement */
            foreach ($spec['statements'] as $statement) {
                $statements[] = StatementFactory::build($statement);
            }
        }

        $alias = $spec['alias'] ?? null;

        return new self($from, $statements, $alias);
    }

    /**
     * @return Amendment[]
     */
    protected function getAmendableCollection(): array
    {
        return (array) $this->statements;
    }

    /**
     * @param From        $from
     * @param null|string $alias
     *
     * @return string
     */
    private function header(From $from, ?string $alias): string
    {
        $template = '%1$s'.((null !== $alias) ? ' AS %2$s' : null);

        return \sprintf($template, $from->dump(), $alias);
    }
}
