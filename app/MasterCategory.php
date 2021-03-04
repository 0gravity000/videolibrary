<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MasterCategory extends Model
{
    //
    public function categories()
    {
        return $this->hasMany('App\Category');
    }
}
