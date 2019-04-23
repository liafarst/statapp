<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;

class ProductController extends Controller {

  public function getProducts() {
    $products = Product::where('user_id', '1')->get();

    return view('pages.input')->with('products', $products);
  }


}
