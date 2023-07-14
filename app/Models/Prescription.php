<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    use HasFactory;

    protected $table = 'prescription';

    public $fillable = [
        'created_by',
        'coe_id',
        'patient_user_id',
        'fulfilled_by',
        'fulfilled_on',
        'creator_comment',
        'fulfiller_comment',
        'status',
    ];

    public function prescriptionProducts()
    {
        return $this->hasMany(PrescriptionProduct::class, 'prescription_id');
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function pharmacist()
    {
        return $this->belongsTo(User::class, 'fulfilled_by');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'patient_user_id');
    }

    public function hospital()
    {
        return $this->belongsTo(COE::class, 'coe_id');
    }
}
