<?php
namespace App\Modules\Modules;

class   Module extends \Eloquent {

    //protected $table = 'posts';

   /* public $attributes = array('type'=>'page');*/

    protected $fillable = array('name','slug','status');

    public $timestamps = false;



}