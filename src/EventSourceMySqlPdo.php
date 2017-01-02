<?php namespace jpuck\calendar;

use PDO;
use Carbon\Carbon;

class EventSourceMySqlPdo implements EventSource
{
    protected $pdo;
    protected $events = [];

    public function __construct(PDO $pdo, array $options = null)
    {
        $this->pdo = $pdo;
        $this->cast();
    }

    protected function select() : array
    {
        // TODO: get categories
        $sql = 'SELECT * FROM `events` ORDER BY `start`';
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    protected function cast()
    {
        foreach ( $this->select() as $event ) {
            $options = [];

            if ( ! empty($event['finish']) ) {
                $options['finish'] = new Carbon($event['finish']);
            }

            if ( ! empty($event['description']) ) {
                $options['description'] = $event['description'];
            }

            // TODO: get categories

            $this->events []= new Event(
                (int)$event['id'],
                new Carbon($event['start']),
                $event['title'],
                $options
            );
        }
    }

    public function fetch() : array
    {
        return $this->events;
    }
}
