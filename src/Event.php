<?php namespace jpuck\calendar;

use Carbon\Carbon;

class Event
{
    protected $id;
    protected $start;
    protected $finish;
    protected $title;
    protected $description;
    protected $categories;

    public function __construct(int $id, Carbon $start, string $title, array $options = null)
    {
        $this->setId($id);
        $this->setStart($start);
        $this->setTitle($title);

        if ( isset($options['finish']) ) {
            $this->setFinish($options['finish']);
        }

        if ( isset($options['description']) ) {
            $this->setDescription($options['description']);
        }

        if ( isset($options['categories']) ) {
            $this->setCategories($options['categories']);
        }
    }

    public function __get($property)
    {
        return $this->$property;
    }

    public function setId(int $id) : Event
    {
        $this->id = $id;
        return $this;
    }

    public function setStart(Carbon $start) : Event
    {
        $this->start = $start;
        return $this;
    }

    public function setFinish(Carbon $finish) : Event
    {
        $this->finish = $finish;
        return $this;
    }

    public function setTitle(string $title) : Event
    {
        $this->title = $title;
        return $this;
    }

    public function setDescription(string $description) : Event
    {
        $this->description = $description;
        return $this;
    }

    public function setCategories(array $categories) : Event
    {
        $this->categories = [];

        foreach ($categories as $category) {
            $this->addCategory($category);
        }

        return $this;
    }

    public function addCategory(Category $category) : Event
    {
        $this->categories []= $category;
        return $this;
    }
}
