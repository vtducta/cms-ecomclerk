<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 6/20/14
 * Time: 11:06 AM
 */

namespace App\Modules\Modules;



use App\Services\Validation\Laravel\LaravelValidator;
use App\Services\Validation\ValidationService;

class ModuleValidator extends LaravelValidator implements ValidationService {



    /**
     * Validation for creating a new User
     *
     * @var array
     */
    protected $rules = array(
        'default'=>array(
            'name' => 'required',
        ),


    );

} 