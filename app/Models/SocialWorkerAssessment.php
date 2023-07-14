<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialWorkerAssessment extends Model
{
    use HasFactory;

    protected $table= "social_worker_assessments";

    protected $fillable=[
        'patient_id',
        'user_id',
        'appearance',
        'bmi',
        'comment_on_home',
        'comment_on_environment',
        'comment_on_fammily',
        'general_comment',
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
