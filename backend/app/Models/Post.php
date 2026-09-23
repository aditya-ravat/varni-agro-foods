<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Post extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uid', 'slug', 'title', 'excerpt', 'body_html', 'hero_image',
        'tags', 'author_id', 'published_at', 'status',
        'seo_title', 'seo_description', 'og_image', 'reading_minutes',
    ];

    protected $casts = ['tags' => 'array', 'published_at' => 'datetime'];

    protected static function booted(): void
    {
        static::creating(function (Post $p) {
            $p->uid ??= (string) Str::ulid();
            $p->slug ??= Str::slug($p->title);
        });
    }

    public function getRouteKeyName(): string { return 'slug'; }

    public function author() { return $this->belongsTo(User::class, 'author_id'); }

    public function scopePublished($q)
    {
        return $q->where('status', 'published')->where('published_at', '<=', now());
    }
}
