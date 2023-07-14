<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalHistory extends Model
{
    use HasFactory;

    protected $table="personal_history";

    protected $fillable=[
            'patient_id',
            'user_id',
            'average_income_per_month',
            'average_eat_daily',
            'who_provides_feeding',
            'have_accomodation',
            'type_of_accomodation',
            'no_of_good_set_of_cloths',
            'how_you_get_them',
            'where_you_receive_care',
            'why_choose_center_above',
            'level_of_spousal_support',
            'other_support',
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
