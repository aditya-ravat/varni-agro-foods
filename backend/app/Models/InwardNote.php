<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class InwardNote extends Model
{
    protected $fillable = [
        'uid', 'reference', 'lot_id', 'booking_id', 'gate_in_at',
        'vehicle_no', 'driver_name', 'driver_phone',
        'weighbridge_in_kg', 'weighbridge_out_kg', 'photos',
        'received_by', 'remarks',
    ];

    protected $casts = [
        'gate_in_at' => 'datetime',
        'photos' => 'array',
        'weighbridge_in_kg' => 'decimal:3',
        'weighbridge_out_kg' => 'decimal:3',
    ];

    protected static function booted(): void
    {
        static::creating(function (InwardNote $n) {
            $n->uid ??= (string) Str::ulid();
            $n->reference ??= 'IN/'.now()->format('y/m').'/'.str_pad((string) (static::max('id') + 1), 5, '0', STR_PAD_LEFT);
        });
    }

    public function lot() { return $this->belongsTo(Lot::class); }
    public function booking() { return $this->belongsTo(Booking::class); }
}
