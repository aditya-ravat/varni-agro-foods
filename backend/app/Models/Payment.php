<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Payment extends Model
{
    protected $fillable = [
        'uid', 'reference', 'customer_id', 'invoice_id', 'mode',
        'gateway_reference', 'gateway_payload', 'amount', 'paid_at', 'status', 'notes',
    ];

    protected $casts = [
        'gateway_payload' => 'array',
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (Payment $p) {
            $p->uid ??= (string) Str::ulid();
            $p->reference ??= 'PMT/'.now()->format('y/m').'/'.str_pad((string) (static::max('id') + 1), 5, '0', STR_PAD_LEFT);
        });
    }

    public function customer() { return $this->belongsTo(Customer::class); }
    public function invoice() { return $this->belongsTo(Invoice::class); }
}
