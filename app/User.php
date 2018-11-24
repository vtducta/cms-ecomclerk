<?php

namespace App;

use App\Modules\Users\Traits\UserAuths;
use App\Modules\Users\Traits\UserHelper;
use App\Modules\Users\Traits\UserRelations;
use App\Modules\Users\Traits\UserScopes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Optimait\Laravel\Traits\CreatedUpdatedTrait;

class User extends Authenticatable
{
    use Notifiable, CreatedUpdatedTrait, UserAuths, UserScopes, UserRelations, UserHelper;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'password', 'last_name', 'user_type_id', 'phone', 'status'
    ];

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;


    public static $statusLabel = [
        self::STATUS_INACTIVE => '<label class="label label-danger">INACTIVE</label>',
        self::STATUS_ACTIVE => '<label class="label label-success">ACTIVE</label>'
    ];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
}
