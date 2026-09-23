<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Block extends Model
{
    protected $fillable = ['facility_id', 'code', 'name', 'description', 'sort_order'];

    public function facility() { return $this->belongsTo(Facility::class); }
    public function chambers() { return $this->hasMany(Chamber::class); }
}
