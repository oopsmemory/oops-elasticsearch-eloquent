<?php

namespace Isswp101\Persimmon\Event;

class EventEmitter
{
    protected $events = [];

    public function on($event, callable $callback)
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
