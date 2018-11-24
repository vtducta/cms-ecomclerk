<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\Jobs\Job;

class UserJob extends Model
{
    protected $table = 'user_job';
    protected $fillable = [
        'user_id', 'job_id', 'type', 'row_count'
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
