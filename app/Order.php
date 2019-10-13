<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model {

    public $billDate;

    public function product(){
      return $this->belongsTo('App\Product');
    }
    public function bill(){
      return $this->belongsTo('App\Bill');
    }
}
