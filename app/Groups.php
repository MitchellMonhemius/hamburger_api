<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Groups extends Model
{
    public function groupBurgers()
    {
        return $this->belongsTo('App\Burgers', 'group_id');
    }
}
