<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uid', 'user_id', 'code', 'name', 'type', 'phone', 'email',
        'gstin', 'pan', 'fssai', 'credit_limit', 'payment_terms_days',
        'billing_address', 'shipping_address', 'status', 'notes',
        'created_by', 'updated_by',
    ];

    protected $casts = [
        'billing_address' => 'array',
        'shipping_address' => 'array',
        'credit_limit' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::creating(function (Customer $customer) {
            $customer->uid ??= (string) \Illuminate\Support\Str::ulid();
            $customer->code ??= 'CUST-'.str_pad((string) (static::max('id') + 1), 6, '0', STR_PAD_LEFT);
        });
    }

    public function user() { return $this->belongsTo(User::class); }
    public function kycDocuments() { return $this->hasMany(CustomerKycDocument::class); }
    public function bookings() { return $this->hasMany(Booking::class); }
    public function lots() { return $this->hasMany(Lot::class); }
    public function invoices() { return $this->hasMany(Invoice::class); }
}
