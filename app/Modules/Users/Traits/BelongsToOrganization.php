<?php
/**
 * Created by PhpStorm.
 * User: Zorro
 * Date: 6/3/2016
 * Time: 12:40 PM
 */

namespace App\Modules\Users\Traits;


trait BelongsToOrganization {

    public static function bootBelongsToOrganization(){
        /*if the table has user_id just set it to current id*/
        static::creating(function($model){
            /*$model->user_id = \Auth::id();*/
            $model->org_id = activeOrg()->id;

        });
        /*if the table has user_id just set it to current id*/
    }

    public function org(){
        return $this->belongsTo('App\Modules\Organizations\Organization', 'org_id');
    }

    public function scopeFromSameOrg($query){
        return $query->where('org_id', '=', activeOrg()->id);
    }

}