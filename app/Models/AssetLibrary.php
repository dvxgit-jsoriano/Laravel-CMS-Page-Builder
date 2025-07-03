<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetLibrary extends Model
{
    protected $guarded = [];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
