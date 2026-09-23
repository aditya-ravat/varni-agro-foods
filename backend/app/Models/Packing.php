<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Packing extends Model
{
    protected $fillable = ['name', 'code', 'default_weight_kg', 'default_volume_m3', 'is_active'];
    protected $casts = ['is_active' => 'boolean'];
}
