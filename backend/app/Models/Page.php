<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Page extends Model
{
    protected $fillable = ['slug', 'title', 'body_html', 'seo_title', 'seo_description', 'og_image', 'is_published'];
    protected $casts = ['is_published' => 'boolean'];

    protected static function booted(): void
    {
        static::creating(fn (Page $p) => $p->slug ??= Str::slug($p->title));
    }

    public function getRouteKeyName(): string { return 'slug'; }
}
