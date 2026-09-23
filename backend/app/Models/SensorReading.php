<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SensorReading extends Model
{
    protected $fillable = ['chamber_id', 'recorded_at', 'temperature_c', 'humidity_pct', 'door_open', 'power_ok', 'source'];
    protected $casts = [
        'recorded_at' => 'datetime',
        'temperature_c' => 'decimal:2',
        'humidity_pct' => 'decimal:2',
        'door_open' => 'boolean',
        'power_ok' => 'boolean',
    ];

    public function chamber() { return $this->belongsTo(Chamber::class); }
}
