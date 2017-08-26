<?php

namespace Ntzm\UselessCommentFinder\Console;

use Ntzm\UselessCommentFinder\Console\Command\FindCommand;
use Symfony\Component\Console\Application as BaseApplication;

final class Application extends BaseApplication
{
    public const VERSION = '0.0.0';

    public function __construct()
    {
        parent::__construct('Useless Comment Finder', self::VERSION);

        $this->add(new FindCommand());
    }
}
