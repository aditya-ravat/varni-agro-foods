<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Commodity extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uid', 'category_id', 'name', 'slug', 'hsn_code', 'gst_percent',
        'recommended_temp_min_c', 'recommended_temp_max_c',
        'recommended_humidity_min', 'recommended_humidity_max',
        'shelf_life_days', 'image', 'icon', 'short_description', 'body_html',
        'faqs', 'seo_title', 'seo_description', 'og_image',
        'is_published', 'is_featured', 'sort_order',
    ];

    protected $casts = [
        'faqs' => 'array',
        'is_published' => 'boolean',
        'is_featured' => 'boolean',
        'gst_percent' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::creating(function (Commodity $c) {
            $c->uid ??= (string) Str::ulid();
            $c->slug ??= Str::slug($c->name);
        });
    }

    public function getRouteKeyName(): string { return 'slug'; }

    public function category() { return $this->belongsTo(CommodityCategory::class, 'category_id'); }
    public function tariffs() { return $this->hasMany(Tariff::class); }

    public function scopePublished($q) { return $q->where('is_published', true); }
    public function scopeFeatured($q) { return $q->where('is_featured', true); }
}
