<?php
/**
 * Created by PhpStorm.
 * User: monkeyDluffy
 * Date: 2/12/2016
 * Time: 10:24 PM
 */

namespace App\Modules\Users\Traits;


trait UserHelper
{


    public function getPermissionsAttribute($value)
    {
        return json_decode($value);
    }

    public function setPermissionsAttribute($value)
    {
        $this->attributes['permissions'] = json_encode($value);
    }


    public function getProfilePicUrl($size = array(100, 100))
    {
        if ($this->photo_id != 0 && $this->photo_id != '') {
            return asset(@$this->photo->media->folder . $size[0] . 'X' . $size[1]. @$this->photo->media->filename);
        } else {
            return asset('assets/backend/img/avatar.png');
        }
    }


    public function getProfilePic($class = 'img-thumbnail', $size=array(100, 100)){
        return '<img src="'.$this->getProfilePicUrl($size).'" class="'.$class.'">';
    }


    public function setUserType($typeId)
    {
        $this->user_type_id = $typeId;
    }


    public function fullName(){
        return $this->first_name.' '.$this->last_name;
    }


}