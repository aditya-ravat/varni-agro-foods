<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class StorageLocation extends Model
{
    protected $fillable = ['chamber_id', 'code', 'row', 'level', 'position', 'capacity_units', 'used_units', 'qr_token', 'status'];

    protected static function booted(): void
    {
        static::creating(fn (StorageLocation $l) => $l->qr_token ??= Str::random(24));
    }

    public function chamber() { return $this->belongsTo(Chamber::class); }
    public function lotLocations() { return $this->hasMany(LotLocation::class); }
}
