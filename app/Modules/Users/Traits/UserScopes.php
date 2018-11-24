<?php
/**
 * Created by PhpStorm.
 * User: monkeyDluffy
 * Date: 2/3/2016
 * Time: 7:52 PM
 */

namespace App\Modules\Users\Traits;


use App\Modules\Users\Types\UserType;
use App\User;

trait UserScopes
{


    public function scopeForMe($query)
    {

        if (auth()->user()->isAdmin()) {
            return $query;
        } else {
            return $query->where('created_by', '=', auth()->user()->id);
        }
    }

    public function scopeExceptAdmin($query)
    {
        return $query->where('user_type_id', '!=', UserType::ADMIN);
    }

    public function scopeClients($q)
    {
        return $q->where('user_type_id', '=', UserType::CLIENT);
    }


    public function scopeExceptMe($query)
    {
        return $query->where('id', '!=', \Auth::id());
    }


    public function scopeActive($query)
    {
        return $query->where('status', '=', 1);
    }

    public function scopeNonClients($q)
    {
        return $q->where('user_type_id', '!=', UserType::CLIENT);
    }


    public function scopeAdmin($query)
    {
        return $query->where('user_type_id', '=', UserType::ADMIN);
    }
}