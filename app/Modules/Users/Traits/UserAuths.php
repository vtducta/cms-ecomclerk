<?php
/**
 * Created by PhpStorm.
 * User: monkeyDluffy
 * Date: 2/3/2016
 * Time: 7:54 PM
 */

namespace App\Modules\Users\Traits;


use App\Modules\Users\Types\UserType;
use App\User;

trait UserAuths
{
    public function isAdmin()
    {
        return $this->user_type_id == UserType::ADMIN;
    }

    public function isClient()
    {
        return $this->user_type_id == UserType::CLIENT;
    }

    public function isActive(){
        if (($this->status == 1 && $this->user_type_id == UserType::CLIENT) || $this->user_type_id == UserType::ADMIN)
        {
            return true;
        }
    }

}