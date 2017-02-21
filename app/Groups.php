<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Groups extends Model
{
    public function groupBurgers()
    {
        return $this->belongsTo('App\Burgers', 'group_id');
    }

    public function groupChefs()
    {
        return $this->belongsTo('App\User');
    }


    public static function getUserByGroup($group_id)
    {
    	$query = "
    			  SELECT users.*

    			  FROM members 

    			  JOIN users
    			  ON members.chef_id=users.id

    			  WHERE members.group_id=$group_id
    			";

    	$result = DB::select(DB::raw($query));

    	return $result;
    }

}
