<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Facility extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uid', 'code', 'name', 'slug', 'tagline', 'description', 'body_html',
        'address_line1', 'address_line2', 'city', 'state', 'country', 'pincode',
        'latitude', 'longitude', 'gstin', 'phone', 'email', 'whatsapp',
        'opening_hours', 'total_capacity_mt', 'manager_id', 'hero_image',
        'seo_title', 'seo_description', 'og_image', 'is_published', 'sort_order',
    ];

    protected $casts = [
        'opening_hours' => 'array',
        'is_published' => 'boolean',
        'total_capacity_mt' => 'decimal:2',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
    ];

    protected static function booted(): void
    {
        static::creating(function (Facility $f) {
            $f->uid ??= (string) Str::ulid();
            $f->slug ??= Str::slug($f->name);
        });
    }

    public function getRouteKeyName(): string { return 'slug'; }

    public function manager() { return $this->belongsTo(User::class, 'manager_id'); }
    public function blocks() { return $this->hasMany(Block::class); }
    public function chambers() { return $this->hasMany(Chamber::class); }
    public function certifications() { return $this->hasMany(FacilityCertification::class); }
    public function lots() { return $this->hasMany(Lot::class); }

    public function scopePublished($q) { return $q->where('is_published', true); }
}
