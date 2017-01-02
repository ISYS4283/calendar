<?php namespace jpuck\calendar;

use PDO;

class EventSourceMySqlPdo implements EventSource
{
    protected $pdo;

    public function __construct(PDO $pdo, array $options = null)
    {
        $this->pdo = $pdo;
    }

    public function fetch() : Event
    {
        return new Event;
    }
}
