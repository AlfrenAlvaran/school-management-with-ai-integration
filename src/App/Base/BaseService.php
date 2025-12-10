<?php

namespace App\Base;

class BaseService 
{
    protected $model;

    public function __construct($model)
    {
        $this->model = new $model();
    }
}