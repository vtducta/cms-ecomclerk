<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\Jobs\Job;

class UserImportHistory extends Model
{
    protected $table = 'user_import_history';
    protected $fillable = [
        'user_id', 'job_id', 'row', 'attribute', 'message'
    ];

    public function user()
    {
        $this->belongsTo( '\App\User');
    }

    public function job()
    {
        $this->hasOne(Job::class);
    }
}
