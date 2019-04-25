<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\MainCategory;
use App\SpecificCategory;

class ProductController extends Controller {

  public function getProducts() {
    $products = Product::where('user_id', '1')->get();
    $mainCategories = MainCategory::all();
    $specificCategories = SpecificCategory::orderBy('name', 'asc')->get();

    $data = [
      'products' => $products,
      'main-categories' => $mainCategories,
      'specific-categories' => $specificCategories
    ];

    return view('pages.input')->with('data', $data);
  }


}
