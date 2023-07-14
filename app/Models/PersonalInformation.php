<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalInformation extends Model
{
    use HasFactory;

    protected $table="personal_information";

    protected $fillable=[
        'patient_id',
        'user_id',
        'nhis_no',
        'gender',
        'age',
        'ethnicity',
        'marital_status',
        'no_of_children',
        'level_of_education',
        'religion',
        'occupation',
        'status',
        'application_review_id'
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class,'patient_id','id');
    }

    public function applicationReview()
    {
        return $this->belongsTo(ApplicationReview::class,'application_review_id');
    }
}
