<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('pages.home');
});

Route::get('/new-bill', 'ProductController@getProducts');

Route::get('/charts', function () {
    return view('pages.charts');
});

Route::get('/bill-history', 'OrderController@getBills');

Route::get('/bill-history/edit/{billID}', 'OrderController@showBill');

Route::get('/my-products', 'ProductController@indexProducts');

Route::get('/locations', 'LocationController@getLocations');

Route::get('/charts/by-product', 'OrderController@spendingsByProduct');

Route::post('/create-bill', 'OrderController@createBill');

Route::post('/update-bill', 'OrderController@updateBill');

Route::post('/update-products', 'ProductController@updateProducts');

Route::post('/update-locations', 'LocationController@updateLocations');

Route::post('/delete-bill', 'OrderController@deleteBill');

Route::post('/delete-order', 'OrderController@deleteOrder');

Route::post('/delete-product', 'ProductController@deleteProduct');

Route::post('/delete-location', 'LocationController@deleteLocation');
