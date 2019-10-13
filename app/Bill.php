<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bill extends Model {
    public function orders(){
      return $this->hasMany('App\Order');
    }

    public function location(){
      return $this->belongsTo('App\Location');
    }
}
