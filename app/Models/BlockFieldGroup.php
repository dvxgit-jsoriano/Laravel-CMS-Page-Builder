<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlockFieldGroup extends Model
{
    protected $guarded = [];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function block_field_group_items()
    {
        return $this->hasMany(BlockFieldGroupItem::class, 'block_field_group_id');
    }
}
