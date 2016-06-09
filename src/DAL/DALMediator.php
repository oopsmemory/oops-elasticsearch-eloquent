<?php

namespace Isswp101\Persimmon\DAL;

class DALMediator
{
    const EVENT_BEFORE_SEARCH = 'EVENT_BEFORE_SEARCH';
    const EVENT_AFTER_SEARCH = 'EVENT_AFTER_SEARCH';

    protected $events = [];

    public function attach($event, callable $callback)
    {
        if (!isset($this->events[$event])) {
            $this->events[$event] = [];
        }
        $this->events[$event][] = $callback;
    }

    public function trigger($event, $data = null)
    {
        if (isset($this->events[$event])) {
            foreach ($this->events[$event] as $callback) {
                $callback($data);
            }
        }
    }
}
