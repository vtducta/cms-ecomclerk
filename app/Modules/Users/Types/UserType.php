<?php
namespace App\Modules\Users\Types;


class UserType extends \Eloquent
{
    const ADMIN = 10;
    const CLIENT = 1;



    protected $table = 'user_types';

    protected $fillable = ['title'];

    public $timestamps = false;



    public function selfDestruct()
    {
        return $this->delete();
    }

} 