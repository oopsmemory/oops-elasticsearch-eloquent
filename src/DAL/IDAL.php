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
     * @return bool
     */
    public function has($id);

    /**
     * @param mixed $id
     * @param array $options
     * @return Model
     */
    public function get($id, array $options = []);

    /**
     * @param Model $instance
     * @return mixed Inserted id.
     */
    public function put(Model $instance);

    /**
     * @param mixed $id
     * @return mixed
     */
    public function delete($id);
}