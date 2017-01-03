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
        $calendar = $this->getMonthBlocks();

        return $this->twig->loadTemplate("$view.twig.html")->render($calendar);
    }

    protected function getMonthBlocks() : array
    {
        $months = [];

        foreach ( $this->events as $event ) {
            $month = $event->start->format('F Y');
            if ( empty( $months[$month] ) ) {
                $months[$month] = $this->getEmptyMonth($event->start);
            }

            $months[$month][$event->start->day]['events'] []= $event;
        }

        foreach ( $months as $key => &$month ) {
            $this->padMonthBlocks($month, new Carbon($key));
        }

        return ['months' => $months];
    }

    protected function padMonthBlocks(array &$month, Carbon $date)
    {
        $pre = [];

        // pad leading blank days for even blocks of 7
        $first_day_of_week_in_month = $date->startOfMonth()->dayOfWeek;
        while ( $first_day_of_week_in_month-- ) {
            $pre []= null;
        }

        $month = array_merge($pre, $month);

        // pad trailing blank days for even blocks of 7
        while ( count($month) % 7 ) {
            $month []= null;
        }
    }

    protected function getEmptyMonth(Carbon $date) : array
    {
        $days_in_month = (int)$date->format('t');
        for ( $i = 1; $i <= $days_in_month; ++$i ) {
            $month[$i] = ['date' => $i];
        }

        return $month;
    }

    public function __toString()
    {
        return $this->render();
    }
}
