<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'content',
        'campaign_id',
        'creator_user_id',
        'edit_user_id',
    ];

    public function creatorUser()
    {
        return $this->belongsTo(User::class, 'creator_user_id');
    }

    public function editUser()
    {
        return $this->belongsTo(User::class, 'edit_user_id');
    }

    public function campaign()
    {
        return $this->belongsTo(User::class, 'edit_user_id');
    }
}
