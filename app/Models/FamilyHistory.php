<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FamilyHistory extends Model
{
    use HasFactory;

    protected $table = 'family_history';

    protected $fillable=[
        'patient_id',
        'user_id',
        'family_set_up',
        'family_size',
        'birth_order',
        'father_education_status',
        'mother_education_status',
        'fathers_occupation',
        'mothers_occupation',
        'level_of_family_care',
        'family_total_income_month',
        'status',
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
