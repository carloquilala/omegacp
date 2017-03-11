<?php

namespace artworx\omegacp\Models;

use Illuminate\Database\Eloquent\Model;
use artworx\omegacp\Facades\Omega;

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
