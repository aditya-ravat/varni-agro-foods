<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Invoice extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uid', 'number', 'customer_id', 'facility_id', 'rent_run_id',
        'date', 'due_date', 'period_start', 'period_end',
        'subtotal', 'tax_total', 'discount_total', 'total',
        'paid_total', 'balance', 'status', 'pdf_path', 'terms', 'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'due_date' => 'date',
        'period_start' => 'date',
        'period_end' => 'date',
        'subtotal' => 'decimal:2',
        'tax_total' => 'decimal:2',
        'discount_total' => 'decimal:2',
        'total' => 'decimal:2',
        'paid_total' => 'decimal:2',
        'balance' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::creating(function (Invoice $i) {
            $i->uid ??= (string) Str::ulid();
            $fy = now()->month >= 4 ? now()->year : now()->year - 1;
            $fyCode = substr((string) $fy, 2, 2).substr((string) ($fy + 1), 2, 2);
            $i->number ??= "INV/{$fyCode}/".str_pad((string) (static::max('id') + 1), 6, '0', STR_PAD_LEFT);
        });
    }

    public function customer() { return $this->belongsTo(Customer::class); }
    public function facility() { return $this->belongsTo(Facility::class); }
    public function lines() { return $this->hasMany(InvoiceLine::class); }
    public function payments() { return $this->hasMany(Payment::class); }
}
