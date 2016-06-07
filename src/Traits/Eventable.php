<?php

namespace Isswp101\Persimmon\Traits;

trait Eventable
{
    protected function saving()
    {
        return true;
    }

    protected function saved()
    {
        return true;
    }

    protected function updating()
    {
        return true;
    }

    protected function updated()
    {
        return true;
    }

    protected function creating()
    {
        return true;
    }

    protected function created()
    {
        return true;
    }

    protected function deleting()
    {
        return true;
    }

    protected function deleted()
    {
        return true;
    }
}
