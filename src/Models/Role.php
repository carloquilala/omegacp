<?php

namespace AI\Omega\Models;

use Illuminate\Database\Eloquent\Model;
use AI\Omega\Facades\Omega;

class Role extends Model
{
    protected $guarded = [];

    public function users()
    {
        return $this->belongsToMany(Omega::modelClass('User'), 'user_roles');
    }

    public function permissions()
    {
        return $this->belongsToMany(Omega::modelClass('Permission'));
    }
}
