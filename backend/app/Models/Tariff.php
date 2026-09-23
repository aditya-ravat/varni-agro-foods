<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tariff extends Model
{
    protected $fillable = [
        'season_id', 'commodity_id', 'packing_id', 'chamber_type',
        'rate', 'basis', 'min_charge', 'advance_percent', 'is_active',
    ];

    protected $casts = [
        'rate' => 'decimal:2',
        'min_charge' => 'decimal:2',
        'advance_percent' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function season() { return $this->belongsTo(Season::class); }
    public function commodity() { return $this->belongsTo(Commodity::class); }
    public function packing() { return $this->belongsTo(Packing::class); }
    public function surcharges() { return $this->hasMany(TariffSurcharge::class); }
}
