<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/vendors/paginate', [
    'as' => 'vendors.paginate',
    'uses' => 'Admin\VendorController@paginate'
]);

Route::get('/vendor-products/paginate', [
    'as' => 'vendor-products.paginate',
    'uses' => 'Admin\VendorProductController@paginate'
]);

Route::get('/fbaproducts/paginate', [
    'as' => 'fbaproducts.paginate',
    'uses' => 'Admin\ProductFBAController@paginate'
]);

Route::get('/purchaseorders/paginate/{id}', [
    'as' => 'webpanel.purchaseorders.paginate',
    'uses' => 'Admin\PurchaseOrdersController@paginate'
]);





