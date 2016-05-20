<?php

namespace Isswp101\Persimmon\Traits;

use Illuminate\Contracts\Logging\Log;
use Monolog\Logger;

trait Logable
{
    /**
     * @var Logger
     */
    public $_logger;

    /**
     * @return bool
     */
    public function hasLogger()
    {
        return !is_null($this->_logger);
    }

    /**
     * @return Logger
     */
    public function getLogger()
    {
        return $this->_logger;
    }

    /**
     * @param Log $logger
     */
    public function injectLogger(Log $logger)
    {
        $this->_logger = $logger;
    }
}