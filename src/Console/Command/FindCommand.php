<?php

namespace Ntzm\UselessCommentFinder\Console\Command;

use Ntzm\UselessCommentFinder\Classifier\DocCommentClassifier;
use Ntzm\UselessCommentFinder\Classifier\EmptyCommentClassifier;
use Ntzm\UselessCommentFinder\Classifier\NoteClassifier;
use Ntzm\UselessCommentFinder\Classifier\ShortCommentClassifier;
use Ntzm\UselessCommentFinder\Comment\Comment;
use Ntzm\UselessCommentFinder\Comment\Finder as CommentFinder;
use Ntzm\UselessCommentFinder\Comment\Normalizer;
use Ntzm\UselessCommentFinder\Comment\TypeDetector;
use Ntzm\UselessCommentFinder\Violation;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Stopwatch\Stopwatch;

final class FindCommand extends Command
{
    private $commentFinder;
    private $commentNormalizer;
    private $commentTypeDetector;
    private $stopwatch;
    private $classifiers = [];

    public function __construct()
    {
        parent::__construct();

        $this->commentFinder = new CommentFinder();
        $this->commentNormalizer = new Normalizer();
        $this->commentTypeDetector = new TypeDetector();
        $this->stopwatch = new Stopwatch();

        $classifiers = [
            DocCommentClassifier::class,
            EmptyCommentClassifier::class,
            NoteClassifier::class,
            ShortCommentClassifier::class,
        ];

        foreach ($classifiers as $classifier) {
            $this->classifiers[] = new $classifier();
        }
    }

    protected function configure(): void
    {
        $this
            ->setName('find')
            ->setDefinition([
                new InputArgument('path', InputArgument::IS_ARRAY, 'The path.'),
            ])
            ->setDescription('Finds useless comments')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $path = $input->getArgument('path');

        $finder = new Finder();

        $finder->in($path)->name('*.php');

        $violations = [];

        $this->stopwatch->start('find');

        foreach ($finder as $file) {
            $violations = array_merge($violations, $this->getViolations($file));
        }

        $event = $this->stopwatch->stop('find');

        $timeInSeconds = $event->getDuration() / 1000;
        $memoryInMegabytes = $event->getMemory() / 1024 / 1024;

        if (empty($violations)) {
            $output->writeln("Time: {$timeInSeconds} seconds");
            $output->writeln("Memory: {$memoryInMegabytes} MB");

            return 0;
        }

        foreach ($violations as $violation) {
            $output->writeln("Potentially useless comment found in {$violation->getFile()->getPathname()} on line {$violation->getComment()->getLine()}");
        }

        $output->writeln("Time: {$timeInSeconds} seconds");
        $output->writeln("Memory: {$memoryInMegabytes} MB");

        return 1;
    }

    private function getViolations(SplFileInfo $file): array
    {
        $comments = $this->commentFinder->find($file->getContents());

        $violations = [];

        foreach ($comments as $token) {
            $comment = new Comment(
                $this->commentNormalizer->normalize($token[1]),
                $token[2],
                $this->commentTypeDetector->detect($token[1])
            );

            foreach ($this->classifiers as $classifier) {
                $isUseless = $classifier->isUseless($comment);

                if ($isUseless === null) {
                    continue;
                }

                if ($isUseless === true) {
                    $violations[] = new Violation($comment, $file);

                    continue 2;
                }

                if ($isUseless === false) {
                    continue 2;
                }
            }
        }

        return $violations;
    }
}
