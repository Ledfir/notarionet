<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    public function subMenus()
    {
    	return $this->hasMany('App\Models\Menu','parent');
    }
}
