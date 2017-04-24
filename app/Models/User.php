<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;
use App\QueryFilter;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'avatar',
        'password',
        'is_active',
        'star',
        'token_verification',
        'phone_number',
        'role',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * @var array
     */
    public $rules = [
        'name' => 'required|max:255',
        'email' => 'required|email|max:255|unique:users',
        'password' => 'required|min:6|confirmed',
        'phone_number' => 'required|numeric|phone',
        'password' => 'required|min:6|max:30|confirmed',
        'password_confirmation' => 'min:6'
    ];

    public $loginRules = [
        'email' => 'required|email',
        'password' => 'required|min:6',
    ];

    public function updateRules($id)
    {
        return [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'avatar' => ['mimes:jpg,jpeg,JPEG,png,gif', 'max:2024'],
            'password' => 'min:6|max:30|confirmed',
            'password_confirmation' => 'min:6',
            'phone_number' => 'required|numeric|phone',
        ];
    }

    public function getAvatarAttribute($value)
    {
        if (empty($value)) {
            return config('path.to_avatar_default');
        }

        $pattern = "/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i";
        preg_match($pattern, $value, $matches);

        if (!empty($matches)) {
            return $value;
        }

        return config('path.to_avatar') . $value;
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->is_active = config('constants.NOT_ACTIVE');
            $user->token_verification = str_random(20);
        });
    }

    public function isCurrent()
    {
        return $this->id == auth()->id();
    }

    public function userCampaign()
    {
        return $this->hasOne(UserCampaign::class);
    }

    public function followers($userId)
    {
        if (!$userId) {
            return false;
        }

        return Relationship::where('target_id', $userId)
            ->where('target_type', config('constants.FOLLOW_USER'))
            ->where('status', config('constants.ACTIVATED'))
            ->count();
    }

    public function checkFollow($targetId)
    {
        if (!$targetId) {
            return false;
        }

        return Relationship::where('user_id', $this->id)
            ->where('target_id', $targetId)
            ->where('status', config('constants.ACTIVATED'))
            ->first();
    }

    public function isAdmin()
    {
        return $this->role == config('settings.role.admin');
    }

    public function showRole()
    {
        return trans('user.role')[$this->role];
    }

    public function scopeFilter($query, QueryFilter $filters)
    {
        return $filters->apply($query);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}
