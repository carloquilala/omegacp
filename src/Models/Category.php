<?php

namespace artworx\omegacp\Models;

use Illuminate\Database\Eloquent\Model;
use artworx\omegacp\Facades\Omega;

class Category extends Model
{
    protected $table = 'categories';

    protected $fillable = ['slug', 'name'];

    public function posts()
    {
        return $this->hasMany(Omega::modelClass('Post'))
            ->published()
            ->orderBy('created_at', 'DESC');
    }

    public function parentId()
    {
        return $this->belongsTo(self::class);
    }
}
