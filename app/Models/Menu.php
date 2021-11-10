<?php

namespace App\Models;

use App\Models\BaseModel;

class Menu extends BaseModel
{
	function relrole()
	{
    	return $this->hasMany(\App\Models\RoleMenu::class, 'menu_id', 'id');
    }
}