<?php

namespace App\Modules\VendorProducts;


use Optimait\Laravel\Services\Validation\Laravel\LaravelValidator;
use Optimait\Laravel\Services\Validation\ValidationService;

class VendorProductsValidator extends LaravelValidator implements ValidationService
{

    /*
     * Validation for creating a new User
     *
     * @var array
     */
    protected $rules = array(
        'default' => array(
            'product_title' =>  'required',
            "vendor_item_number" => 'required',
            'upc' => 'required',
            'vendor_cost' => 'required',
            'case_quantity' => 'required',
            'weight' => 'required',
            'category' => 'required'
        ),

        'edit' => array(
            'product_title' =>  'required',
            "vendor_item_number" => 'required',
            'upc' => 'required',
            'vendor_cost' => 'required',
            'case_quantity' => 'required',
            'weight' => 'required',
            'category' => 'required'
        ),

        'import' => array(
            'file' => 'required'
        ),
    );
}