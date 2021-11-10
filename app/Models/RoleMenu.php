<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleMenu extends Model
{
    function relmenu()
    {
    	return $this->hasOne(\App\Models\Menu::class, 'id', 'menu_id')->where('show_all', '0');
    }
}