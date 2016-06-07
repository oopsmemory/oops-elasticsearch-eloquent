<?php

namespace Isswp101\Persimmon\Traits;

trait Userable
{
    /**
     * @var int
     */
    public $user_id;

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @param int $user_id
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }
}
