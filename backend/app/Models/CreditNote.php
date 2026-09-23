<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CreditNote extends Model
{
    protected $fillable = ['number', 'customer_id', 'invoice_id', 'date', 'amount', 'reason'];
    protected $casts = ['date' => 'date', 'amount' => 'decimal:2'];

    public function customer() { return $this->belongsTo(Customer::class); }
    public function invoice() { return $this->belongsTo(Invoice::class); }
}
