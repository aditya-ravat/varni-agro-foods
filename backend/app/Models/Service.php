<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Service extends Model
{
    protected $fillable = [
        'name', 'slug', 'icon', 'image', 'short_description', 'body_html', 'faqs',
        'seo_title', 'seo_description', 'og_image', 'is_published', 'sort_order',
    ];
    protected $casts = ['faqs' => 'array', 'is_published' => 'boolean'];

    protected static function booted(): void
    {
        static::creating(fn (Service $s) => $s->slug ??= Str::slug($s->name));
    }

    public function getRouteKeyName(): string { return 'slug'; }

    public function scopePublished($q) { return $q->where('is_published', true); }
}
