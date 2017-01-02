<?php namespace jpuck\calendar;

use InvalidArgumentException;
use Twig_Environment;
use Twig_Loader_Filesystem;

class Calendar
{
    protected $events;
    protected $twig;

    public function __construct(EventSource $events, array $options = null)
    {
        $this->events = $events;

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

    public function render(string $view = 'default') : string
    {
        $data = [];

        return $this->twig->loadTemplate("$view.twig.html")->render($data);
    }

    public function __toString()
    {
        return $this->render();
    }
}
