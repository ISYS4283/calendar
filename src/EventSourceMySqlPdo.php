<?php namespace jpuck\calendar;

use PDO;
use Carbon\Carbon;

class EventSourceMySqlPdo implements EventSource
{
    protected $pdo;

    public function __construct(PDO $pdo, array $options = null)
    {
        $this->pdo = $pdo;
    }

    public function fetch() : Event
    {
        $id = 42;
        $start = new Carbon;
        $title = 'Something Huge';
        return new Event($id, $start, $title);
    }
}
