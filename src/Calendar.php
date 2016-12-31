<?php namespace jpuck\calendar;

use InvalidArgumentException;
use Twig_Environment;
use Twig_Loader_Filesystem;

class Calendar
{
    protected $db;
    protected $twig;

    public function __construct(\PDO $db, array $options = null)
    {
        $this->db = $db;

        if ( isset($options['views']) ) {
            $this->setViewsDirectory($options['views']);
        } else {
            $this->setViewsDirectory(__DIR__.'/../views');
        }
    }

    public function setViewsDirectory(string $views) : Calendar
    {
        if ( ! is_dir($views) ) {
            throw new InvalidArgumentException("$views is not a directory.");
        }

        if ( ! is_readable($views) ) {
            throw new InvalidArgumentException("$views is not readable.");
        }

        $loader = new Twig_Loader_Filesystem($views);

        $this->twig = new Twig_Environment($loader);

        return $this;
    }
}
