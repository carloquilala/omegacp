<?php

namespace AI\Omega\Models;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as AuthUser;
use AI\Omega\Traits\OmegaUser;

class User extends AuthUser
{
    use OmegaUser;

    protected $guarded = [];

    /**
     * On save make sure to set the default avatar if image is not set.
     */
    public function save(array $options = [])
    {
        // If no avatar has been set, set it to the default
        $this->avatar = $this->avatar ?: config('omega.user.default_avatar', 'users/default.png');

        parent::save();
    }

    public function setCreatedAtAttribute($value)
    {
        $this->attributes['created_at'] = Carbon::parse($value)->format('Y-m-d H:i:s');
    }
}
