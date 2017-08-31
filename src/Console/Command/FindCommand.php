<?php

namespace Ntzm\PhpUcf\Console\Command;

use Ntzm\PhpUcf\Classifier\DocCommentClassifier;
use Ntzm\PhpUcf\Classifier\EmptyCommentClassifier;
use Ntzm\PhpUcf\Classifier\NoteClassifier;
use Ntzm\PhpUcf\Classifier\ShortCommentClassifier;
use Ntzm\PhpUcf\Comment\Comment;
use Ntzm\PhpUcf\Comment\Finder as CommentFinder;
use Ntzm\PhpUcf\Comment\Normalizer;
use Ntzm\PhpUcf\Comment\TypeDetector;
use Ntzm\PhpUcf\Report\JsonReporter;
use Ntzm\PhpUcf\Report\Summary;
use Ntzm\PhpUcf\Report\TextReporter;
use Ntzm\PhpUcf\Violation;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Stopwatch\Stopwatch;

final class FindCommand extends Command
{
    private const REPORT_FORMAT_MAP = [
        'txt' => TextReporter::class,
        'json' => JsonReporter::class,
    ];

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
                new InputOption('format', '', InputOption::VALUE_REQUIRED, 'To output results in other formats.'),
            ])
            ->setDescription('Finds useless comments')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $path = $input->getArgument('path');
        $format = $input->getOption('format') ?? 'txt';

        $reporterClass = self::REPORT_FORMAT_MAP[$format];

        /** @var \Ntzm\PhpUcf\Report\ReporterInterface $reporter */
        $reporter = new $reporterClass();

        $finder = new Finder();

        $finder->in($path)->name('*.php');

        $violations = [];

        $this->stopwatch->start('find');

        foreach ($finder as $file) {
            $violations = array_merge($violations, $this->getViolations($file));
        }

        $event = $this->stopwatch->stop('find');

        $summary = new Summary($violations, $event->getDuration(), $event->getMemory());

        $output->write($reporter->generate($summary));

        return empty($violations) ? 0 : 1;
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
