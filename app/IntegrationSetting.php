<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IntegrationSetting extends Model
{
    protected $table = 'integration_setting';
    protected $fillable = [
        'option_key', 'option_value', 'user_id', 'integration_id'
    ];

    public function user()
    {
        $this->belongsTo('\App\User');
    }

    public function integration()
    {
        $this->belongsTo('\App\Integration');
    }
}
