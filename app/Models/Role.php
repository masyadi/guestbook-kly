<?php

namespace App\Models;

use App\Models\BaseModel;

class Role extends BaseModel
{
    function relaccess()
    {
    	return $this->hasMany(\App\Models\RoleMenu::class, 'role_id', 'id')->select('menu_id', 'role_id')->with(['relmenu']);
    }

	public static function superAdmin()
	{
		return self::where('slug', 'super-admin')->first();
	}

    public static function admin()
	{
		return self::where('slug', 'admin')->first();
	}

    public static function reseller()
	{
		return self::where('slug', 'reseller')->first();
	}
}