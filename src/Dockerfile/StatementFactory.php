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

use Webmozart\Assert\Assert;

/**
 * Class StatementFactory.
 */
class StatementFactory
{
    private static $types = [
        'comment' => ['class' => Statement\Comment::class, 'paramsRequired' => true],
        'copy' => ['class' => Statement\Copy::class, 'paramsRequired' => true],
        'entrypoint' => ['class' => Statement\Entrypoint::class, 'paramsRequired' => true],
        'env' => ['class' => Statement\Env::class, 'paramsRequired' => true],
        'from' => ['class' => Statement\From::class, 'paramsRequired' => true],
        'healthcheck' => ['class' => Statement\Healthcheck::class, 'paramsRequired' => true],
        'run' => ['class' => Statement\Run::class, 'paramsRequired' => true],
        'workdir' => ['class' => Statement\Workdir::class, 'paramsRequired' => true],
    ];

    /**
     * @param string[] $spec
     *
     * @return Statement
     */
    public static function build(array $spec): Statement
    {
        Assert::keyExists($spec, 'type', 'Statement requires a "type" property');
        $type = $spec['type'];
        Assert::keyExists(self::$types, $type, \sprintf('Statement type "%1$s" unknown unsupported', $type));
        $class = self::$types[$type]['class'];

        $params = null;
        if (true === self::$types[$type]['paramsRequired']) {
            Assert::keyExists($spec, 'params', \sprintf('Statement type "%1$s" required "params" property', $type));
            $params = $spec['params'];
        }

        return \call_user_func([$class, 'build'], $params);
    }
}
