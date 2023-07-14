<?php

namespace App\Models;

use App\Traits\tUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\StageApprovalAmount;

class Patient extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'patient';

    protected $fillable = [
        'identification_id',
        'user_id',
        'identification_number',
        'identification_document',
        'phone_no_alt',
        'ailment_id',
        'coe_id',
        'state_id',
        'lga_id',
        'address',
        'city',
        'chf_id',
        'state_of_residence',
        'nhis_no',
        'occupation',
        'marital_status',
        'no_of_children',
        'ethnicity',
        'level_of_education',
        'religion',
        'ailment_stage',
        'primary_physician',
        'primary_physician_status',
        'primary_physician_reviewed_on',
        'primary_physician_reviewer_id',
        'social_worker_status',
        'social_worker_reviewed_on',
        'cmd_reviewed_on',
        'cmd_reviewer_id',
        'cmd_review_status',
        'mdt_recommended_fund',
        'care_plan',
    ];

    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function primaryPhysician(){
        return $this->belongsTo(User::class,'primary_physician','id');
    }

    public function roles(){
        return $this->hasMany(Role::class,'user_id','role_id');
    }

    public function customPermissions(){
        return $this->hasMany(Permission::class,'user_id','permission_id');
    }

    public function ailment(){
        return $this->belongsTo(Ailment::class);
    }

    public function applicationReview(){
        return $this->hasMany(ApplicationReview::class,'user_id','user_id');
    }

    public function state(){
        return $this->belongsTo(State::class);
    }

    public function stateOfResidence(){
        return $this->hasOne(State::class,'id','state_of_residence');
    }

    public function coe(){
        return $this->belongsTo(COE::class);
    }

    public function reviewer(){
        return $this->hasOne(ApplicationReview::class);
    }

    public function familyHistories()
    {
        return $this->hasMany(FamilyHistory::class,'patient_id');
    }

    public function personalInformation()
    {
        return $this->hasMany(PersonalInformation::class,'patient_id');
    }

    public function personalHistories()
    {
        return $this->hasMany(PersonalHistory::class,'patient_id');
    }

    public function nextOfKin()
    {
        return $this->hasOne(NextOfKin::class,'patient_id');
    }

    public function socialConditions()
    {
        return $this->hasMany(SocialCondition::class,'patient_id');
    }

    public function SupportAssessments()
    {
        return $this->hasMany(SupportAssessment::class,'patient_id');
    }

    public function SupportWorkerAssessments()
    {
        return $this->hasMany(SupportWorkerAssessment::class,'patient_id');
    }
    public function approvableFund(){
        return StageApprovalAmount::where('stage',$this->ailment_stage)->first()->amount;
    }

    public function patientAppointments()
    {
        return $this->hasMany(PatientAppointment::class,'patient_id');
    }

    public function mdtComments(){
        return $this->hasMany(MDTComment::class,'patient_user_id');
    }

    public function cmdReviewer(){
        return $this->belongsTo(User::class,'cmd_reviewer_id');
    }

    public function wallet()
    {
        return $this->hasOne(Wallet::class, 'user_id', 'user_id');
    }

}
