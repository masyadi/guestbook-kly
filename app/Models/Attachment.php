<?php

namespace App\Models;

use App\Models\BaseModel;

class Attachment extends BaseModel
{
    protected $appends = ['paramsJson'];

    public function getParamsJsonAttribute()
    {
        return $this->params ? json_decode($this->params) : null;
    }
}