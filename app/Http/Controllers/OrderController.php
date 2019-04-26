<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Order;
use App\Product;
use App\Bill;

class OrderController extends Controller {

  public function createOrders(){
    $orders = request()->input('orders');

    // create bill
    $bill = new Bill();
    $bill->user_id = 1;
    $bill->location_id = 1;
    $bill->total = 0;

    $bill->save();

    $total = 0;

    foreach($orders as $o){

      // check if product exists
      $product = Product::where('user_id', '1')->where('name', $o['product_name'])->first();
      $productID = "";
      if($product){
        $productID = $product->id;
      } else {
        $newProduct = new Product();

        $newProduct->user_id = 1;
        $newProduct->name = $o['product_name'];
        $newProduct->saved_price = 0;
        $newProduct->favourite = 0;
        $newProduct->main_cat_id = $o['main_cat_id'];
        $newProduct->specific_cat_id = $o['specific_cat_id'];

        $newProduct->save();

        $productID = $newProduct->id;
      }

      // create order(s)
      $order = new Order();

      $order->product_id = $productID;
      $order->quantity = $o['quantity'];
      $order->quantity_type = $o['quantity_type'];
      $order->price = $o['price'];
      $order->bill_id = $bill->id;

      $total +=$order->price;

      $order->save();
    }

    // fix total
    $total = round($total, 2);
    $bill->total = $total;

    $bill->save();

    return response()->json([
      'status' => 'Successful',
    ]);
  }

}
