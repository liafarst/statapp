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
    foreach($products as $product){
      if($product['main_cat_id'] == 0){
        $product['icon'] = '/public/images/Default.ico';
      } else if($product['specific_cat_id'] == 0) {
        $product['icon'] = '/public/images/' . MainCategory::find($product['main_cat_id'])['name'] . '.ico';
      } else {
        $product['icon'] = '/public/images/' . SpecificCategory::find($product['specific_cat_id'])['name'] . $product['main_cat_id'] . '.ico';
      }
    }

    $data = [
      'products' => $products,
      'main-categories' => $mainCategories,
      'specific-categories' => $specificCategories
    ];

    return view('pages.input')->with('data', $data);
  }

  public function indexProducts(Request $request) {

    $status = $request->input('status');

    $products = Product::where('user_id', '1')->orderBy('name', 'asc')->get();
    $mainCategories = MainCategory::all();
    $specificCategories = SpecificCategory::orderBy('name', 'asc')->get();

    // set icons
    foreach($products as $product){
      if($product['main_cat_id'] == 0){
        $product['icon'] = '/public/images/Default.ico';
      } else if($product['specific_cat_id'] == 0) {
        $product['icon'] = '/public/images/' . MainCategory::find($product['main_cat_id'])['name'] . '.ico';
      } else {
        $product['icon'] = '/public/images/' . SpecificCategory::find($product['specific_cat_id'])['name'] . $product['main_cat_id'] . '.ico';
      }
    }

    $data = [
      'products' => $products,
      'main-categories' => $mainCategories,
      'specific-categories' => $specificCategories,
      'status' => $status
    ];

    return view('pages.products')->with('data', $data);
  }

  public function deleteProduct() {
    $productID = request()->input('productID');

    $product = Product::find($productID);

    if($product){
      foreach($product->orders as $order){
        if(sizeof($order->bill->orders) == 0){
          $order->bill->delete();
        } else {
          $bill = $order->bill;
          $total = $bill->total - $order->price;
          $bill->total = round($total, 2);
          $bill->save();
        }
        $order->delete();
      }
      $product->delete();
    }

    response(null, 204);
  }

  public function updateProducts(){
    $productIDs = request()->input('productIDs');
    $productNamesOld = request()->input('productNamesOld');
    $productFavoritesOld = request()->input('productFavoritesOld');
    $productMainCatsOld = request()->input('productMainCatsOld');
    $productSpecificCatsOld = request()->input('productSpecificCatsOld');
    $productNamesNew = request()->input('productNamesNew');
    $productFavoritesNew = request()->input('productFavoritesNew');
    $productMainCatsNew = request()->input('productMainCatsNew');
    $productSpecificCatsNew = request()->input('productSpecificCatsNew');

    foreach($productIDs as $i=>$productID){
      $product = Product::find($productID);
      if($product){
        $product->name = $productNamesOld[$i];
        if($productFavoritesOld[$i] === "true"){
          $product->favourite = 1;
        } else {
          $product->favourite = 0;
        }
        $product->main_cat_id = $productMainCatsOld[$i];
        $product->specific_cat_id = $productSpecificCatsOld[$i];
        $product->save();
      }
    }

    if($productNamesNew && $productMainCatsNew && $productSpecificCatsNew && $productFavoritesNew){
      for($i = 0; $i < sizeof($productNamesNew); $i++){
        $product = new Product();
        $product->user_id = 1;
        $product->saved_price = 0;
        $product->name = $productNamesNew[$i];
        if($productFavoritesNew[$i] === "true"){
          $product->favourite = 1;
        } else {
          $product->favourite = 0;
        }
        $product->main_cat_id = $productMainCatsNew[$i];
        $product->specific_cat_id = $productSpecificCatsNew[$i];
        $product->save();
      }
    }

    response(null, 204);

  }

}
