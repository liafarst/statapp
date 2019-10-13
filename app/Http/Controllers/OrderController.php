<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Order;
use App\Bill;
use App\MainCategory;
use App\SpecificCategory;
use App\Location;

class OrderController extends Controller {

  public function createBill(){
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

  public function getBills(Request $request){

    $status = $request->input('status');

    $locations = Location::where('user_id', '1')->get();
    $products = Product::where('user_id', '1')->get();
    $mainCategories = MainCategory::all();
    $specificCategories = SpecificCategory::orderBy('name', 'asc')->get();

    $bills = Bill::where('user_id', '1')->orderBy('created_at', 'desc')->get();
    foreach($bills as $i=>$bill){
      if(count($bill->orders) == 0){
        unset($bills[$i]);
      }
    }

    $data = [
      'bills' => $bills,
      'status' => $status,
      'locations' => $locations,
      'products' => $products,
      'main-categories' => $mainCategories,
      'specific-categories' => $specificCategories
    ];

    return view('pages.history')->with('data', $data);
  }

  public function deleteBill(){
    $billID = request()->input('billID');

    $bill = Bill::find($billID);

    $orders = Order::where('bill_id', $bill->id);
    $orders->delete();

    $bill->delete();

    response(null, 204);
  }

  public function showBill(Request $request, $billID){
    $bill = Bill::find($billID);

    $locations = Location::where('user_id', '1')->orderBy('name', 'asc')->get();
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
      'bill' => $bill,
      'locations' => $locations,
      'products' => $products,
      'main-categories' => $mainCategories,
      'specific-categories' => $specificCategories
    ];

    return view('pages.edit-bill')->with('data', $data);
  }

  public function deleteOrder(){
    $orderID = request()->input('orderID');

    $order = Order::find($orderID);

    // fix bill total
    $bill = Bill::find($order->bill_id);
    $bill->total = $bill->total - $order->price;
    $bill->total = round($bill->total, 2);
    $bill->save();

    $order->delete();

    response(null, 204);
  }

  public function updateBill(){
    $date = request()->input('date');
    $locationID = request()->input('locationID');
    $orderIDsOld = request()->input('orderIDsOld');
    $productsOld = request()->input('productsOld');
    $quantitiesOld = request()->input('quantitiesOld');
    $quantityTypesOld = request()->input('quantityTypesOld');
    $pricesOld = request()->input('pricesOld');
    $productsNew = request()->input('productsNew');
    $quantitiesNew = request()->input('quantitiesNew');
    $quantityTypesNew = request()->input('quantityTypesNew');
    $pricesNew = request()->input('pricesNew');


    $billID = Order::find($orderIDsOld[0])->bill_id;
    $bill = Bill::find($billID);
    $total = 0;
    for($i = 0; $i < sizeof($orderIDsOld); $i++){

      $order = Order::find($orderIDsOld[$i]);

      $product = Product::where('user_id', '1')->where('name', $productsOld[$i])->first();
      $productID = "";
      if($product){
        $productID = $product->id;
      } else {
        $newProduct = new Product();

        $newProduct->user_id = 1;
        $newProduct->name = $productsOld[$i];
        $newProduct->saved_price = 0;
        $newProduct->favourite = 0;
        $newProduct->main_cat_id = 0;
        $newProduct->specific_cat_id = 0;

        $newProduct->save();

        $productID = $newProduct->id;
      }
      $order->product_id = $productID;
      $order->quantity = $quantitiesOld[$i];
      $order->quantity_type = $quantityTypesOld[$i];
      $order->price = $pricesOld[$i];

      $total +=$order->price;

      $order->save();

    }

    if($productsNew){

      for($i = 0; $i < sizeof($productsNew); $i++){

        $order = new Order();

        $product = Product::where('user_id', '1')->where('name', $productsNew[$i])->first();
        $productID = "";
        if($product){
          $productID = $product->id;
        } else {
          $newProduct = new Product();

          $newProduct->user_id = 1;
          $newProduct->name = $productsNew[$i];
          $newProduct->saved_price = 0;
          $newProduct->favourite = 0;
          $newProduct->main_cat_id = 0;
          $newProduct->specific_cat_id = 0;

          $newProduct->save();

          $productID = $newProduct->id;
        }

        $order->product_id = $productID;
        $order->quantity = $quantitiesNew[$i];
        $order->quantity_type = $quantityTypesNew[$i];
        $order->price = $pricesNew[$i];
        $order->bill_id = $billID;

        $total +=$order->price;

        $order->save();

      }

    }

    $bill->location_id = $locationID;

    $timestamp = \Carbon\Carbon::createFromFormat('Y-m-d', $date)->timestamp;
    $bill->created_at = $timestamp;

    $total = round($total, 2);
    $bill->total = $total;

    $bill->save();

    return response()->json([
      'status' => 'Successful',
    ]);

  }

  public function spendingsByProduct(){

    $orders = Order::whereHas('product', function($query) {
        $query->where('user_id', 1);
    })->get();

    foreach($orders as $order){
      $order->bill;
    }

    $data = [
      'orders' => $orders
    ];

    return view('pages.charts-by-product')->with('data', $data);
  }

}
