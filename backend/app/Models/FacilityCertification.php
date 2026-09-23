<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacilityCertification extends Model
{
    protected $fillable = ['facility_id', 'name', 'issuing_body', 'certificate_no', 'issued_on', 'expires_on', 'document_path'];
    protected $casts = ['issued_on' => 'date', 'expires_on' => 'date'];

    public function facility() { return $this->belongsTo(Facility::class); }
}
