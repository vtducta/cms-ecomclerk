<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Integration extends Model
{
    protected $table = 'integration';
    protected $fillable = [
        'name', 'logo', 'url_key'
    ];
}
