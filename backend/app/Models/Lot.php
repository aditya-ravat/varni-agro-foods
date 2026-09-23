<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Lot extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uid', 'lot_number', 'booking_id', 'customer_id', 'facility_id',
        'commodity_id', 'packing_id', 'season_id', 'intake_date',
        'intake_qty', 'balance_qty', 'gross_weight_kg', 'tare_weight_kg',
        'net_weight_kg', 'grade', 'status', 'notes',
    ];

    protected $casts = [
        'intake_date' => 'date',
        'intake_qty' => 'decimal:2',
        'balance_qty' => 'decimal:2',
        'gross_weight_kg' => 'decimal:3',
        'tare_weight_kg' => 'decimal:3',
        'net_weight_kg' => 'decimal:3',
    ];

    protected static function booted(): void
    {
        static::creating(function (Lot $l) {
            $l->uid ??= (string) Str::ulid();
            if (! $l->lot_number) {
                $facility = $l->facility ?: Facility::find($l->facility_id);
                $season = $l->season ?: ($l->season_id ? Season::find($l->season_id) : null);
                $facCode = $facility?->code ?? 'VAR';
                $seasonCode = $season?->code ?? 'GEN';
                $seq = str_pad((string) (static::max('id') + 1), 5, '0', STR_PAD_LEFT);
                $l->lot_number = "{$facCode}/{$seasonCode}/{$seq}";
            }
        });
    }

    public function booking() { return $this->belongsTo(Booking::class); }
    public function customer() { return $this->belongsTo(Customer::class); }
    public function facility() { return $this->belongsTo(Facility::class); }
    public function commodity() { return $this->belongsTo(Commodity::class); }
    public function packing() { return $this->belongsTo(Packing::class); }
    public function season() { return $this->belongsTo(Season::class); }
    public function locations() { return $this->hasMany(LotLocation::class); }
    public function inwardNotes() { return $this->hasMany(InwardNote::class); }
    public function outwardNotes() { return $this->hasMany(OutwardNote::class); }
    public function movements() { return $this->hasMany(Movement::class); }
    public function qualityChecks() { return $this->hasMany(QualityCheck::class); }
}
