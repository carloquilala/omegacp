<?php

namespace AI\Omega\Models;

use Illuminate\Database\Eloquent\Model;

class DataRow extends Model
{
    protected $table = 'data_rows';

    protected $guarded = [];

    public $timestamps = false;
}
