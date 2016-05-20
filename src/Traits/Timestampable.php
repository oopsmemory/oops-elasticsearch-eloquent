<?php

namespace Isswp101\Persimmon\Traits;

use Carbon\Carbon;

trait Timestampable
{
    /**
     * @var Carbon
     */
    public $created_at;

    /**
     * @var Carbon
     */
    public $updated_at;

    /**
     * @return Carbon
     */
    public function getCreatedAt()
    {
        return Carbon::parse($this->created_at);
    }

    /**
     * @param string $created_at
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }

    /**
     * @return Carbon
     */
    public function getUpdatedAt()
    {
        return Carbon::parse($this->updated_at);
    }

    /**
     * @param string $updated_at
     */
    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
    }

    protected function fillTimestamp()
    {
        $utc = Carbon::now('UTC')->toDateTimeString();

        $this->setUpdatedAt($utc);

        if (!$this->_exist) {
            $this->setCreatedAt($utc);
        }
    }
}