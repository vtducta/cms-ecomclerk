<?php

namespace App\Modules\Products;

use Optimait\Laravel\Services\Validation\Laravel\LaravelValidator;
use Optimait\Laravel\Services\Validation\ValidationService;

class ProductValidator extends LaravelValidator implements ValidationService
{
    /**
     * Validation for creating a new User
     *
     * @var array
     */
    protected $rules = array(
        'default' => array(
            'name' => 'required',
            'service_id' => 'required'
        ),
        'import' => array(
            'file' => 'required'
        ),
    );
}