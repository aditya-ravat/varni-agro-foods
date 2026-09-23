<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerKycDocument extends Model
{
    protected $fillable = ['customer_id', 'kind', 'number', 'expires_on', 'status', 'remarks'];
    protected $casts = ['expires_on' => 'date'];

    public function customer() { return $this->belongsTo(Customer::class); }
}
