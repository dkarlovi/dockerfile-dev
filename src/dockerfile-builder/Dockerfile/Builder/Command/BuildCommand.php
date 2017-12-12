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

namespace Dkarlovi\Dockerfile\Builder\Command;

use Dkarlovi\Dockerfile\Builder\Builder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Exception\ParseException;

/**
 * Class BuildCommand.
 */
class BuildCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->setName('dockerfile:build')
            ->setDescription('Build a Dockerfile from a YAML specs file')
            ->addArgument('path', InputArgument::REQUIRED, 'Path to YAML specs file');
    }

    /** @noinspection PhpMissingParentCallCommonInspection */

    /**
     * {@inheritdoc}
     *
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $path = $input->getArgument('path');

        try {
            $dockerfile = Builder::build($path);
        } catch (\InvalidArgumentException $exception) {
            throw new InvalidArgumentException($exception->getMessage(), $exception->getCode(), $exception);
        } catch (ParseException $exception) {
            throw new InvalidArgumentException($exception->getMessage(), $exception->getCode(), $exception);
        }

        echo $dockerfile->dump();
    }
}
