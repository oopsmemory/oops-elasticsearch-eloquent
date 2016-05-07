<?php

namespace Isswp101\Persimmon\Traits;

trait Timestampable
{
    /**
     * @var
     */
    public $created_at;

    /**
     * @var
     */
    public $updated_at;

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        // @TODO: return \Carbon instance
        return $this->created_at;
    }

    /**
     * @param mixed $created_at
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        // @TODO: return \Cabron instance
        return $this->updated_at;
    }

    /**
     * @param mixed $updated_at
     */
    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
    }
}