<?php
namespace App\Modules\Users;


use Optimait\Laravel\Services\Validation\Laravel\LaravelValidator;
use Optimait\Laravel\Services\Validation\ValidationService;

class UserValidator extends LaravelValidator implements ValidationService
{

    /*
     * Validation for creating a new User
     *
     * @var array
     */
    protected $rules = array(
        'default' => array(
            'email' => 'required|email|unique:users',
            'first_name' => 'required',
            'password' => 'required|confirmed',
            'user_type_id' => 'required'
        ),
        'default-no-pass' => array(
            'email' => 'required|email|unique:users',
            'user_type_id' => 'required'
        ),
        'edit' => array(
            'email' => 'required',
            'first_name' => 'required',
            'user_type_id' => 'required'
        ),
        'profile' => array(
            'first_name' => 'required',
            'email' => 'required'

        ),
        'frontend-profile' => array(
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required'

        ),
        'change_password' => array(
            'password' => 'required|confirmed'
        ),
        'client_confirm' => array(
            'terms' => 'required'
        ),
        'client' => array(
            'email' => 'required|email|unique:users',
            'first_name' => 'required',
            'last_name' => 'required'
        ),
        'client-edit' => array(
            'email' => 'required|email',
            'first_name' => 'required',
            'last_name' => 'required'
        ),
        'register-confirmation' => array(
            'email' => 'required|email'
        )

    );
}