<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    protected $table = 'cars';

    // Relacion con el modelo user en el campo user_id de la tbl cars.
    public function user(){
    	return $this->belongsTo('App\User', 'user_id');
    }
}
