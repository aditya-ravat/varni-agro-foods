<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = [
        'name', 'phone', 'email', 'company', 'city',
        'commodity_id', 'facility_id', 'estimated_qty', 'estimated_qty_unit',
        'required_from', 'message', 'source',
        'utm_source', 'utm_medium', 'utm_campaign',
        'status', 'assigned_to', 'notes',
    ];

    protected $casts = ['required_from' => 'date', 'estimated_qty' => 'decimal:2'];

    public function commodity() { return $this->belongsTo(Commodity::class); }
    public function facility() { return $this->belongsTo(Facility::class); }
}
