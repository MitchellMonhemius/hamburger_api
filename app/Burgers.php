<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Burgers extends Model
{
    public function burgerIngredients()
    {
        return $this->belongsTo('App\Ingredients', 'burger_id');
    }
}
