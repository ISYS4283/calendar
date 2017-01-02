<?php namespace jpuck\calendar;

interface EventSource
{
    public function fetch() : Event;
}
