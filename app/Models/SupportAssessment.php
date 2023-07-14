<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportAssessment extends Model
{
    use HasFactory;

    protected $table="support_assessment";

    protected $fillable=[
        'patient_id',
        'user_id',
        'feeding_assistance',
        'medical_assistance',
        'rent_assistance',
        'clothing_assistance',
        'transport_assistance',
        'mobile_bill_assistance',
        'status',
        'points_user_input',
        'points_sys_suggested',
        'application_review_id'
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class,'patient_id','id');
    }

    public function applicationReview()
    {
        return $this->belongsTo(ApplicationReview::class,'application_review_id','id');
    }

}
