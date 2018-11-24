<?php

namespace App\Modules\Vendors;


use Optimait\Laravel\Services\Validation\Laravel\LaravelValidator;
use Optimait\Laravel\Services\Validation\ValidationService;

class VendorValidator extends LaravelValidator implements ValidationService
{

    /*
     * Validation for creating a new User
     *
     * @var array
     */
    protected $rules = array(
        'default' => array(
            'title' => 'required',
            'minimum_purchase_amount' => 'required',
            'minimum_weight_amount' => 'required',
            'minimum_case_quantity' => 'required',
        ),

        'edit' => array(
            'title' => 'required',
            'minimum_purchase_amount' => 'required',
            'minimum_weight_amount' => 'required',
            'minimum_case_quantity' => 'required',
        ),

        'import' => array(
            'file' => 'required'
        ),
    );
}