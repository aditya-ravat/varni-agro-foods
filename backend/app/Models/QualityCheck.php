<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QualityCheck extends Model
{
    protected $fillable = [
        'lot_id', 'checked_at', 'grade', 'sample_qty', 'rejected_qty',
        'parameters', 'photos', 'checked_by', 'notes',
    ];

    protected $casts = [
        'checked_at' => 'datetime',
        'parameters' => 'array',
        'photos' => 'array',
        'sample_qty' => 'decimal:2',
        'rejected_qty' => 'decimal:2',
    ];

    public function lot() { return $this->belongsTo(Lot::class); }
}
