<?php

namespace App\Modules\ProductFBA;


use Optimait\Laravel\Services\Validation\Laravel\LaravelValidator;
use Optimait\Laravel\Services\Validation\ValidationService;

class ProductFbaValidator extends LaravelValidator implements ValidationService
{

    /*
     * Validation for creating a new User
     *
     * @var array
     */
    protected $rules = array(
        'import' => array(
            'file' => 'required'
        ),
    );
}