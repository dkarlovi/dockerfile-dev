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

use Dkarlovi\Dockerfile\Statement\From;
use Dkarlovi\Dockerfile\Statement\StatementFactory;

/**
 * Class Stage.
 */
class Stage implements Dumpable, Buildable
{
    /**
     * @var From
     */
    private $from;

    /**
     * @var null|array
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
        $from = new From($spec['from'][0], $spec['from'][1]);

        $statements = null;
        if ($spec['statements']) {
            /** @var string[][] $specStatements */
            $specStatements = $spec['statements'];
            foreach ($specStatements as $statement) {
                $statements[] = StatementFactory::build($statement);
            }
        }

        return new self($from, $statements);
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
