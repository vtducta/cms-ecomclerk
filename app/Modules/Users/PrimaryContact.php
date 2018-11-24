<?php
/**
 * Created by PhpStorm.
 * User: optima
 * Date: 4/19/17
 * Time: 2:59 PM
 */

namespace App\Modules\Users;


class PrimaryContact extends \Eloquent
{
    protected $table = 'primary_contacts';
    protected $fillable = ['first_name', 'last_name', 'email', 'phone', 'user_id'];

}