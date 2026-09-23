<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SensorAlert extends Model
{
    protected $fillable = ['chamber_id', 'kind', 'severity', 'triggered_at', 'resolved_at', 'payload', 'notified'];
    protected $casts = ['triggered_at' => 'datetime', 'resolved_at' => 'datetime', 'payload' => 'array', 'notified' => 'boolean'];

    public function chamber() { return $this->belongsTo(Chamber::class); }
}
