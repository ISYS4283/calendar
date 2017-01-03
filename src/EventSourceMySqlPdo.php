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

    protected function selectEvents() : array
    {
        $sql = '
            SELECT *
            FROM events
            ORDER BY start
        ';

        $rows = $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

        // set array index as event_id
        foreach ($rows as $row) {
            $result[$row['id']] = $row;
        }

        foreach ( $this->selectCategories() as $category_event ) {
            $result[$category_event['event_id']]['categories'] []= $category_event;
        }

        return $result ?? $rows;
    }

    protected function selectCategories() : array
    {
        $sql = '
            SELECT ce.event_id, ce.category_id, c.name
            FROM category_events ce
            JOIN categories c
              ON ce.category_id = c.id
            ORDER BY ce.event_id, ce.category_id
        ';
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    protected function cast()
    {
        foreach ( $this->selectEvents() as $event ) {
            $options = [];

            if ( ! empty($event['finish']) ) {
                $options['finish'] = new Carbon($event['finish']);
            }

            if ( ! empty($event['description']) ) {
                $options['description'] = $event['description'];
            }

            if ( ! empty($event['categories']) ) {
                foreach ($event['categories'] as $category) {
                    $options['categories'] []= new Category(
                        $category['category_id'],
                        $category['name']
                    );
                }
            }

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
