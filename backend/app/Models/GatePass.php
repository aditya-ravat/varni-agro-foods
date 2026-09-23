<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class GatePass extends Model
{
    protected $fillable = ['uid', 'number', 'type', 'lot_id', 'vehicle_no', 'pdf_path', 'issued_by', 'issued_at'];
    protected $casts = ['issued_at' => 'datetime'];

    protected static function booted(): void
    {
        static::creating(function (GatePass $p) {
            $p->uid ??= (string) Str::ulid();
            $p->number ??= 'GP/'.strtoupper($p->type ?? 'X').'/'.now()->format('y/m').'/'.str_pad((string) (static::max('id') + 1), 5, '0', STR_PAD_LEFT);
        });
    }

    public function lot() { return $this->belongsTo(Lot::class); }
}
