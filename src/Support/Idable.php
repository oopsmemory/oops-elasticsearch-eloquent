<?php

namespace Isswp101\Persimmon\Support;

trait Idable
{
    /**
     * @var mixed
     */
    public $id;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }
}