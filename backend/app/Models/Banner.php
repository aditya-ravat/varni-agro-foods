<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = ['placement', 'title', 'subtitle', 'image_path', 'cta_label', 'cta_url', 'is_active', 'starts_at', 'ends_at', 'sort_order'];
    protected $casts = ['is_active' => 'boolean', 'starts_at' => 'datetime', 'ends_at' => 'datetime'];
}
