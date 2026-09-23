<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class OutwardNote extends Model
{
    protected $fillable = [
        'uid', 'reference', 'lot_id', 'gate_out_at', 'qty',
        'vehicle_no', 'driver_name', 'driver_phone',
        'gate_pass_no', 'e_way_bill_no', 'photos', 'released_by', 'remarks',
    ];

    protected $casts = [
        'gate_out_at' => 'datetime',
        'qty' => 'decimal:2',
        'photos' => 'array',
    ];

    protected static function booted(): void
    {
        static::creating(function (OutwardNote $n) {
            $n->uid ??= (string) Str::ulid();
            $n->reference ??= 'OUT/'.now()->format('y/m').'/'.str_pad((string) (static::max('id') + 1), 5, '0', STR_PAD_LEFT);
        });
    }

    public function lot() { return $this->belongsTo(Lot::class); }
}
