<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /*
    public function master_category()
    {
        return $this->belongsTo('App\MasterCategory');
    }
    */

    //
    public function videos()
    {
        return $this->belongsToMany('App\Video');
    }
}
