<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Chamber extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uid', 'facility_id', 'block_id', 'code', 'name', 'type',
        'temp_min_c', 'temp_max_c', 'humidity_min_pct', 'humidity_max_pct',
        'gross_capacity_mt', 'net_capacity_mt', 'current_occupancy_mt',
        'refrigeration_unit', 'status', 'notes',
    ];

    protected $casts = [
        'temp_min_c' => 'decimal:2',
        'temp_max_c' => 'decimal:2',
        'humidity_min_pct' => 'decimal:2',
        'humidity_max_pct' => 'decimal:2',
        'gross_capacity_mt' => 'decimal:2',
        'net_capacity_mt' => 'decimal:2',
        'current_occupancy_mt' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::creating(fn (Chamber $c) => $c->uid ??= (string) Str::ulid());
    }

    public function facility() { return $this->belongsTo(Facility::class); }
    public function block() { return $this->belongsTo(Block::class); }
    public function locations() { return $this->hasMany(StorageLocation::class); }
    public function sensorReadings() { return $this->hasMany(SensorReading::class); }
    public function alerts() { return $this->hasMany(SensorAlert::class); }

    public function getOccupancyPercentAttribute(): float
    {
        if ($this->net_capacity_mt <= 0) return 0;
        return round(($this->current_occupancy_mt / $this->net_capacity_mt) * 100, 2);
    }
}
