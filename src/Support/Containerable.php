<?php

namespace Isswp101\Persimmon\Support;

trait Containerable
{
    protected $_container;

    final public function container()
    {
        return $this->_container;
    }
}