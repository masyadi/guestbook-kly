<?php

namespace App\Models;

use App\Models\BaseModel;

class GuestBook extends BaseModel
{
    function rel_province()
	{
    	return $this->hasOne(\App\Models\Location::class, 'kode', 'province');
    }

    function rel_city()
	{
    	return $this->hasOne(\App\Models\Location::class, 'kode', 'city');
    }
}
