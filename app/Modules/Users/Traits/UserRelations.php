<?php
/**
 * Created by PhpStorm.
 * User: monkeyDluffy
 * Date: 2/3/2016
 * Time: 7:54 PM
 */

namespace App\Modules\Users\Traits;


use App\Modules\Forums\Discussion;
use App\Modules\Forums\Thread;
use App\Modules\Leads\Lead;
use App\Modules\Users\PrimaryContact;
use App\Modules\Users\Types\UserType;
use App\User;

trait UserRelations
{

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function userType()
    {
        return $this->belongsTo('App\Modules\Users\Types\UserType', 'user_type_id');
    }

    public function forumComments()
    {
        return $this->hasMany(Discussion::class, 'created_by');
    }

    public function threads()
    {
        return $this->hasMany(Thread::class, 'created_by');
    }

    public function primaryContacts()
    {
        return $this->hasMany(PrimaryContact::class, 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function photo()
    {
        return $this->hasOne('Optimait\Laravel\Models\Attachment', 'id', 'photo_id');
    }

    public function documents()
    {
        return $this->morphMany('Optimait\Laravel\Models\Attachment', 'attachable')
            ->where('type', 'LIKE', User::ATTACHMENT_DOCUMENT);
    }

    public function assignedLeads()
    {
        return $this->hasMany(Lead::class, 'user_id');
    }


}