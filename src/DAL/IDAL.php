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
     * @param array $columns
     * @return mixed Inserted id.
     */
    public function put(array $columns = ['*']);

    /**
     * @return mixed
     */
    public function delete();
}
