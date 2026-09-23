<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LotLocation extends Model
{
    protected $fillable = ['lot_id', 'storage_location_id', 'qty'];
    protected $casts = ['qty' => 'decimal:2'];

    public function lot() { return $this->belongsTo(Lot::class); }
    public function location() { return $this->belongsTo(StorageLocation::class, 'storage_location_id'); }
}
