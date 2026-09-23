<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CommodityCategory extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'sort_order'];

    protected static function booted(): void
    {
        static::creating(fn (CommodityCategory $c) => $c->slug ??= Str::slug($c->name));
    }

    public function commodities() { return $this->hasMany(Commodity::class, 'category_id'); }
}
