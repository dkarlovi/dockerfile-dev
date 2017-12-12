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

namespace Dkarlovi\Dockerfile;

use Dkarlovi\Dockerfile\Amendable\AmendableCollectionTrait;
use Dkarlovi\Dockerfile\Amendable\AmendableTrait;
use Dkarlovi\Dockerfile\Statement\From;
use Webmozart\Assert\Assert;

/**
 * Class Stage.
 */
class Stage implements Amendable, AmendableCollection, Amendment, Buildable, Dumpable
{
    use AmendableTrait;
    use AmendableCollectionTrait;

    /**
     * @var From
     */
    private $from;

    /**
     * @var Statement[]
     */
    private $statements = [];

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
        foreach ((array) $statements as $statement) {
            $this->addStatement($statement);
        }
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
            /** @var Statement[] $statements */
            $statements = [];
            /** @var string[] $statement */
            foreach ($spec['statements'] as $statement) {
                $statements[] = StatementFactory::build($statement);
            }
        }

        /** @var string $alias */
        $alias = $spec['alias'] ?? null;

        return new self($from, $statements, $alias);
    }

    /**
     * @return string
     */
    public function getIntent(): string
    {
        // TODO: Implement getIntent() method.
    }

    /**
     * @return mixed
     */
    public function getAmendmentBody()
    {
        // TODO: Implement getAmendmentBody() method.
    }

    /**
     * @return Amendable[]
     */
    protected function getAmendableCollection(): array
    {
        return $this->statements;
    }

    /**
     * @param Amendment $amendment
     */
    protected function amendSelfWith(Amendment $amendment): void
    {
        // TODO: Implement amendSelfWith() method.
    }

    /**
     * @param Statement $statement
     */
    private function addStatement(Statement $statement): void
    {
        $this->statements[] = $statement;
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
