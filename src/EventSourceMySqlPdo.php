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
            // TODO: get optional fields
            $this->events []= new Event(
                (int)$event['id'],
                new Carbon($event['start']),
                $event['title']
            );
        }
    }

    public function fetch() : array
    {
        return $this->events;
    }
}
