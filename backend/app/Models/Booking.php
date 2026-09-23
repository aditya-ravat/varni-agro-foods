<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Booking extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uid', 'reference', 'customer_id', 'facility_id', 'chamber_id',
        'commodity_id', 'packing_id', 'season_id', 'planned_qty',
        'expected_intake_from', 'expected_intake_to', 'expected_release_by',
        'status', 'notes', 'created_by',
    ];

    protected $casts = [
        'expected_intake_from' => 'date',
        'expected_intake_to' => 'date',
        'expected_release_by' => 'date',
        'planned_qty' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::creating(function (Booking $b) {
            $b->uid ??= (string) Str::ulid();
            $b->reference ??= 'BK/'.now()->format('y/m').'/'.str_pad((string) (static::max('id') + 1), 5, '0', STR_PAD_LEFT);
        });
    }

    public function customer() { return $this->belongsTo(Customer::class); }
    public function facility() { return $this->belongsTo(Facility::class); }
    public function chamber() { return $this->belongsTo(Chamber::class); }
    public function commodity() { return $this->belongsTo(Commodity::class); }
    public function packing() { return $this->belongsTo(Packing::class); }
    public function season() { return $this->belongsTo(Season::class); }
    public function lots() { return $this->hasMany(Lot::class); }
}
