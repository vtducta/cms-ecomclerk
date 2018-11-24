<?php
namespace App\Modules\Users\Types;


use App\Services\Validation\Laravel\LaravelValidator;
use App\Services\Validation\ValidationService;

class UserTypeValidator extends LaravelValidator implements ValidationService {

    /**
     * Validation for creating a new UserType
     *
     * @var array
     */
    protected $rules = array(
        'default'=>array(
            'title' => 'required|unique:userTypes',
        )


    );
}