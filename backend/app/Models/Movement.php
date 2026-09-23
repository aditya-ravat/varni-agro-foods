<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movement extends Model
{
    protected $fillable = [
        'lot_id', 'from_location_id', 'to_location_id', 'qty',
        'reason', 'performed_by', 'performed_at', 'notes',
    ];

    protected $casts = [
        'performed_at' => 'datetime',
        'qty' => 'decimal:2',
    ];

    public function lot() { return $this->belongsTo(Lot::class); }
    public function fromLocation() { return $this->belongsTo(StorageLocation::class, 'from_location_id'); }
    public function toLocation() { return $this->belongsTo(StorageLocation::class, 'to_location_id'); }
}
