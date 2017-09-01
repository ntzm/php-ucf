<?php

namespace Ntzm\PhpUcf\Runner;

use Ntzm\PhpUcf\Comment\Comment;
use Ntzm\PhpUcf\Comment\Finder;
use Ntzm\PhpUcf\Comment\Normalizer;
use Ntzm\PhpUcf\Comment\TypeDetector;
use Ntzm\PhpUcf\Violation;
use Symfony\Component\Finder\SplFileInfo;

final class Runner
{
    public function __construct()
    {
        $this->commentFinder = new Finder();
        $this->commentNormalizer = new Normalizer();
        $this->commentTypeDetector = new TypeDetector();
    }

    public function getViolations(iterable $files, array $classifiers): array
    {
        $violations = [];

        foreach ($files as $file) {
            $violations = array_merge(
                $violations,
                $this->getViolationsForFile($file, $classifiers)
            );
        }

        return $violations;
    }

    private function getViolationsForFile(SplFileInfo $file, array $classifiers): array
    {
        $comments = $this->commentFinder->find($file->getContents());

        $violations = [];

        foreach ($comments as $token) {
            $comment = new Comment(
                $this->commentNormalizer->normalize($token[1]),
                $token[2],
                $this->commentTypeDetector->detect($token[1])
            );

            if ($this->isCommentUseless($comment, $classifiers)) {
                $violations[] = new Violation($comment, $file);
            }
        }

        return $violations;
    }

    private function isCommentUseless(Comment $comment, array $classifiers): bool
    {
        /** @var \Ntzm\PhpUcf\Classifier\ClassifierInterface $classifier */
        foreach ($classifiers as $classifier) {
            $result = $classifier->isUseless($comment);

            if ($result === null) {
                continue;
            }

            return $result;
        }

        return false;
    }
}
