<?php

namespace Ntzm\PhpUcf\Console\Command;

use Ntzm\PhpUcf\Classifier\AnnotationClassifier;
use Ntzm\PhpUcf\Classifier\DocCommentClassifier;
use Ntzm\PhpUcf\Classifier\EmptyCommentClassifier;
use Ntzm\PhpUcf\Classifier\NoteClassifier;
use Ntzm\PhpUcf\Classifier\ShortCommentClassifier;
use Ntzm\PhpUcf\Report\JsonReporter;
use Ntzm\PhpUcf\Report\ReporterInterface;
use Ntzm\PhpUcf\Report\Summary;
use Ntzm\PhpUcf\Report\TextReporter;
use Ntzm\PhpUcf\Runner\Runner;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Stopwatch\Stopwatch;

final class FindCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->setName('find')
            ->setDefinition([
                new InputArgument('path', InputArgument::IS_ARRAY, 'The path.'),
                new InputOption('format', '', InputOption::VALUE_REQUIRED, 'To output results in other formats.'),
            ])
            ->setDescription('Finds useless comments')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $path = $input->getArgument('path');
        $reporter = $this->getReporter($input->getOption('format'));

        $stopwatch = new Stopwatch();
        $finder = new Finder();

        $finder->in($path)->name('*.php');

        $stopwatch->start('find');

        $violations = (new Runner())->getViolations(
            $finder,
            $this->getDefaultClassifiers()
        );

        $event = $stopwatch->stop('find');

        $summary = new Summary(
            $violations,
            $event->getDuration(),
            $event->getMemory()
        );

        $output->write($reporter->generate($summary));

        return empty($violations) ? 0 : 1;
    }

    private function getDefaultClassifiers(): array
    {
        return array_map(function (string $classifier) {
            return new $classifier();
        }, [
            DocCommentClassifier::class,
            AnnotationClassifier::class,
            EmptyCommentClassifier::class,
            NoteClassifier::class,
            ShortCommentClassifier::class,
        ]);
    }

    private function getReporter(?string $type): ReporterInterface
    {
        switch ($type) {
            case 'json':
                return new JsonReporter();
            default:
                return new TextReporter();
        }
    }
}
