<?php namespace jpuck\calendar;

class Calendar
{
    protected $db;

    public function __construct(\PDO $db, array $options = null)
    {
        $this->db = $db;
    }
}
