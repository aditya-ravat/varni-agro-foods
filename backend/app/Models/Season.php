<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Season extends Model
{
    protected $fillable = ['code', 'name', 'start_date', 'end_date', 'is_active', 'notes'];
    protected $casts = ['start_date' => 'date', 'end_date' => 'date', 'is_active' => 'boolean'];

    public function tariffs() { return $this->hasMany(Tariff::class); }
}
