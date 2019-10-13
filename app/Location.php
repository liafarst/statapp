<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Location extends Model {

  public $moH;
  public $tuH;
  public $weH;
  public $thH;
  public $frH;
  public $saH;
  public $suH;

  public function bills(){
    return $this->hasMany('App\Bill');
  }
}
