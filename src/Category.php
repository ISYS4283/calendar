<?php namespace jpuck\calendar;

class Category
{
    protected $id;
    protected $name;
    protected $description;

    public function __construct(int $id, string $name, string $description = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
    }

    public function __get($property)
    {
        return $this->$property;
    }
}
