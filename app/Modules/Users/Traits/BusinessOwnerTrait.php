<?php
/**
 * Created by PhpStorm.
 * User: Zorro
 * Date: 6/3/2016
 * Time: 12:40 PM
 */

namespace App\Modules\Users\Traits;


trait BusinessOwnerTrait {

    public static function bootBusinessOwnerTrait(){
        /*if the table has user_id just set it to current id*/
        static::creating(function($model){
            /*$model->user_id = \Auth::id();*/
            $model->parent_id = auth()->user()->id;

        });
        /*if the table has user_id just set it to current id*/
    }

    public function boss(){
        return $this->belongsTo('App\User', 'parent_id');
    }

    public function workers(){
        return $this->hasMany("App\User", 'parent_id');
    }

}