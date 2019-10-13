<?php

namespace App\Helpers;


class CustomHelpers {

  public static function fancyPrice($price) {
    switch(strlen(substr(strrchr($price, "."), 1))){
      case "0":
        $price .= ".00";
        break;
      case "1":
        $price .= "0";
        break;
      default:
        break;
    }
    return $price;
  }


}
