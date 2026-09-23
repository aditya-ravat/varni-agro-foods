<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = ['facility_id', 'category', 'date', 'amount', 'vendor', 'reference', 'attachment_path', 'notes', 'created_by'];
    protected $casts = ['date' => 'date', 'amount' => 'decimal:2'];

    public function facility() { return $this->belongsTo(Facility::class); }
}
