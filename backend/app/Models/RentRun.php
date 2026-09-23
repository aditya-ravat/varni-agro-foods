<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RentRun extends Model
{
    protected $fillable = ['period_start', 'period_end', 'status', 'invoices_generated', 'total_amount', 'errors', 'triggered_by'];
    protected $casts = ['period_start' => 'date', 'period_end' => 'date', 'errors' => 'array', 'total_amount' => 'decimal:2'];

    public function invoices() { return $this->hasMany(Invoice::class); }
}
