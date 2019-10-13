<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model {
    public function orders(){
      return $this->hasMany('App\Order');
    }

    public function mainCat(){
      return $this->belongsTo('App\MainCategory')->orderBy('id', 'asc');
    }

    public function specificCat(){
      return $this->belongsTo('App\SpecificCategory')->orderBy('name', 'asc');
    }
}
