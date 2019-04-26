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

    // set icons
    $testCat = SpecificCategory::find($products[0]['main_cat_id']);
    foreach($products as $product){
      $product['icon'] = '../public/images/' . SpecificCategory::find($product['specific_cat_id'])['name'] . $product['main_cat_id'] . '.ico';
    }

    $data = [
      'products' => $products,
      'main-categories' => $mainCategories,
      'specific-categories' => $specificCategories
    ];

    return view('pages.input')->with('data', $data);
  }


}
