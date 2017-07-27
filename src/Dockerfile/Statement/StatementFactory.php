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

/**
 * Class StatementFactory.
 */
class StatementFactory
{
    /**
     * @param string[] $spec
     *
     * @return Statement
     */
    public static function build(array $spec): Statement
    {
        $class = __NAMESPACE__.'\\'.$spec['type'];
        $params = $spec['params'] ?? null;

        return \call_user_func([$class, 'build'], $params);
    }
}
