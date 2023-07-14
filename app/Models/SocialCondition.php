<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialCondition extends Model
{
    use HasFactory;

    protected $table="social_condition";

    protected $fillable=[
        'patient_id',
        'user_id',
        'have_running_water',
        'type_of_toilet_facility',
        'have_generator_solar',
        'means_of_transportation',
        'have_mobile_phone',
        'how_maintain_phone_use',
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
