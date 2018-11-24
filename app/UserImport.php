<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserImport extends Model
{
    protected $table = 'user_import';
    protected $fillable = [
        'user_id', 'job_id', 'file_name', 'row_count', 'result_file'
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
