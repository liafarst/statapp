<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MainCategory extends Model {
  public function specificCats(){
    return $this->hasMany('App\SpecificCategory', 'main_cat_id', 'id')->orderBy('name', 'asc');
  }
}
