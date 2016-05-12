<?php

namespace Isswp101\Persimmon\DAL;

use Isswp101\Persimmon\Model;

interface IDAL
{
    /**
     * @return Model
     */
    public function getModel();

    /**
     * @param mixed $id
     * @param array $options
     * @return Model
     */
    public function get($id, array $options = []);

    /**
     * @return mixed Inserted id.
     */
    public function put();

    /**
     * @return mixed
     */
    public function delete();
}