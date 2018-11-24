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
        return redirect('login');
    });


    Auth::routes();
    Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('corn/amazon', 'CornController@amazon');
    Route::post('markpo', 'Admin\ProductsController@markPO');

    Route::group(['middleware' => ['admin']], function() {
        Route::get('test', 'HomeController@index');
    });

    /*
     * Admin routes
     */

    Route::group(['middleware' => ['admin'], 'prefix' => 'admin'], function () {

        Route::get('unauthorized', function () {
            return view('webpanel.unauthorized');
        });

        Route::get('users/delete/{id}', 'Admin\UsersController@getDelete');
        Route::resource('users', 'Admin\UsersController', [
            'as' => 'webpanel'
        ]);

    });


    /*
     * Active users routes
     */

    Route::group(['middleware' => ['active']], function() {

        Route::get('/', ['as' => 'dashboard', function () {
            return redirect()->to('dashboard');
        }]);

        Route::get('dashboard', 'Admin\DashboardController@getIndex');

        Route::get('my/profile', array('uses' => 'Admin\UsersController@getProfile'));
        Route::post('my/profile', array('as' => 'admin.profile.update', 'uses' => 'Admin\UsersController@postProfile'));

        Route::get('my/password', array('uses' => 'Admin\UsersController@getChangePassword'));
        Route::post('my/password', array('uses' => 'Admin\UsersController@postChangePassword'));

        /*
         * Products route
         */
        Route::get('products/delete/{id}', 'Admin\ProductsController@getDelete');
        Route::get('products/export', 'Admin\ProductsController@export');
        Route::post('products/import', 'Admin\ProductsController@import');
        Route::get('products/datatable', 'Admin\ProductsController@getList');
        Route::resource('products', 'Admin\ProductsController', [
            'as' => 'webpanel'
        ]);

        /*
         * Reports route
         */
        Route::get('areports', 'Admin\ReportController@index')->name('webpanel.reports.index');
        Route::post('areports/import', 'Admin\ReportController@import')->name('webpanel.reports.import');
        Route::get('areports/fbareports', 'Admin\ReportController@paginate' )->name('reports.paginate');

        /*
         * Vendor route
         */
        Route::get('vendors/export', 'Admin\VendorController@export');
        Route::post('vendors/import', 'Admin\VendorController@import');
        Route::resource('vendors', 'Admin\VendorController', ['as' => 'webpanel']);
        Route::get('vendors/{id}/edit', 'Admin\VendorController@edit', ['as' => 'vendors.edit']);


        /*
         * Vendor products route
         */
        Route::get('vendor-products/{id}/edit', 'Admin\VendorProductController@edit', ['as' => 'vendorProducts.edit']);
        Route::get('vendor-products/export', 'Admin\VendorProductController@export');
        Route::post('vendor-products/import', 'Admin\VendorProductController@import');
        Route::delete('vendor-products/{id}', 'Admin\VendorProductController@destroy', ['as' => 'vendor-products.destroy']);
        Route::resource('vendor-products', 'Admin\VendorProductController', ['as' => 'webpanel']);


        /*
         * FBA products route
         */
        Route::get('fbaproducts/export', 'Admin\ProductFBAController@export');
        Route::post('fbaproducts/import', 'Admin\ProductFBAController@import');
        Route::get('fbaproducts/amazon', 'Admin\ProductFBAController@getAmazonReports');
        Route::get('fbaproducts/getcat', 'Admin\ProductFBAController@getCategoriesForProducts');
        Route::get('fbaproducts/getprice', 'Admin\ProductFBAController@getLatestPricingForASIN');
        Route::get('fbaproducts/scrape', 'Admin\ProductFBAController@getDataFromOnlineDb');
        Route::get('fbaproducts/importmikeamazon', 'Admin\ProductFBAController@importDataIntoMikeAmazon');
        Route::get('fbaproducts/updatestock', 'Admin\ProductFBAController@updateStock');
        Route::get('fbaproducts/cleantitle', 'Admin\ProductFBAController@clearTitle');
        Route::delete('fbaproducts/{id}', 'Admin\ProductFBAController@destroy', ['as' => 'fbaproducts.destroy']);
        Route::resource('fbaproducts', 'Admin\ProductFBAController', ['as' => 'webpanel']);



        /*
         * Purchase order routes
         */
        Route::get('purchaseorders/export/{vendor_id}', 'Admin\PurchaseOrdersController@export');
        Route::resource('purchaseorders','Admin\PurchaseOrdersController',['as' => 'webpanel']);

        /*
         * Integration routes
         */
        Route::resource('integrations', 'Admin\DTIntegrationController', ['as' => 'webpanel']);


        /*
         * Import route
         */
        Route::get('import','Admin\ImportController@index')->name('import');
        Route::post('import', 'Admin\ImportController@import');
        Route::get('import/result_error/{job_id}', 'Admin\ImportController@error_export', ['as' => 'import.export.result_error']);
        Route::get('/import/paginate', [
            'as' => 'import.paginate',
            'uses' => 'Admin\ImportController@paginate'
        ]);
    });
