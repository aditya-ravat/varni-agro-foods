<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TariffSurcharge extends Model
{
    protected $fillable = ['tariff_id', 'name', 'amount', 'type', 'mandatory'];
    protected $casts = ['amount' => 'decimal:2', 'mandatory' => 'boolean'];

    public function tariff() { return $this->belongsTo(Tariff::class); }
}
