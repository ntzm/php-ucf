<?php

namespace Ntzm\UselessCommentFinder\Console\Command;

use Ntzm\UselessCommentFinder\Classifier;
use Ntzm\UselessCommentFinder\Comment\Comment;
use Ntzm\UselessCommentFinder\Comment\Finder as CommentFinder;
use Ntzm\UselessCommentFinder\Comment\Normalizer;
use Ntzm\UselessCommentFinder\Comment\TypeDetector;
use Ntzm\UselessCommentFinder\Config;
use Ntzm\UselessCommentFinder\Violation;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

final class FindCommand extends Command
{
    private $commentFinder;
    private $commentNormalizer;
    private $commentTypeDetector;
    private $commentClassifier;

    public function __construct()
    {
        parent::__construct();

        $this->commentFinder = new CommentFinder();
        $this->commentNormalizer = new Normalizer();
        $this->commentTypeDetector = new TypeDetector();
        $this->commentClassifier = new Classifier(new Config());
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

        foreach ($finder as $file) {
            $violations = array_merge($violations, $this->getViolations($file));
        }

        if (empty($violations)) {
            return 0;
        }

        foreach ($violations as $violation) {
            $output->writeln("Potentially useless comment found in {$violation->getFile()->getPathname()} on line {$violation->getComment()->getLine()}");
        }

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

            if ($this->commentClassifier->isUseless($comment)) {
                $violations[] = new Violation($comment, $file);
            }
        }

        return $violations;
    }
}
