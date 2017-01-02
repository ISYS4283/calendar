<?php namespace jpuck\calendar;

use InvalidArgumentException;
use Twig_Environment;
use Twig_Loader_Filesystem;
use Carbon\Carbon;

class Calendar
{
    protected $events;
    protected $twig;

    public function __construct(EventSource $events, array $options = null)
    {
        $this->events = $events->fetch();

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
        $first_event = $this->events[0] ?? new Carbon;
        $first_month = $first_event->start->format('F Y');

        $calendar = [
            'months' => [
                $first_month => $this->getEmptyMonth($first_event->start),
            ],
        ];

        return $this->twig->loadTemplate("$view.twig.html")->render($calendar);
    }

    protected function getEmptyMonth(Carbon $date) : array
    {
        $month = [];

        // pad leading blank days for even blocks of 7
        $first_day_of_week_in_month = $date->startOfMonth()->dayOfWeek;
        while ( $first_day_of_week_in_month-- ) {
            $month []= null;
        }

        $days_in_month = (int)$date->format('t');
        for ( $i = 1; $i <= $days_in_month; ++$i ) {
            $month []= ['date' => $i];
        }

        while ( count($month) % 7 ) {
            $month []= null;
        }

        return $month;
    }

    public function __toString()
    {
        return $this->render();
    }
}
