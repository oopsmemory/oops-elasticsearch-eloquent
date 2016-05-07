<?php

namespace Isswp101\Persimmon\Traits;

use Isswp101\Persimmon\Container\Container;

trait Containerable
{
    /**
     * @var Container
     */
    protected $_container;

    /**
     * Return Container instance.
     *
     * @return Container
     */
    final protected function container()
    {
        return $this->_container;
    }

    /**
     * Create a new Container instance.
     */
    protected function initContainer()
    {
        $this->_container = new Container();
    }
}