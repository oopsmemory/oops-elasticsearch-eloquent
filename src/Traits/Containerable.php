<?php

namespace Isswp101\Persimmon\Traits;

trait Containerable
{
    protected $_container;

    final public function container()
    {
        return $this->_container;
    }
}