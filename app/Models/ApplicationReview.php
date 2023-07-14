<?php

namespace App\Models;

use App\Traits\tUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApplicationReview extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'application_review';

    protected $fillable = [
        'amount_approved',
        'user_id',
        'reason',
        'patient_id',
        'coe_id',
        'ailment_id',
        'reviewed_by',
        'reviewed_on',
        'status'
    ];

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function coe()
    {
        return $this->belongsTo(COE::class);
    }

    public function ailment()
    {
        return $this->belongsTo(Ailment::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'user_id', 'user_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function familyHistory()
    {
        return $this->hasOne(FamilyHistory::class, 'application_review_id');
    }

    public function personalHistory()
    {
        return $this->hasOne(PersonalHistory::class, 'application_review_id');
    }

    public function personalInformation()
    {
        return $this->hasOne(PersonalInformation::class, 'application_review_id');
    }

    public function socialCondition()
    {
        return $this->hasOne(SocialCondition::class, 'application_review_id');
    }

    public function socialWorkerAssessment()
    {
        return $this->hasOne(SocialWorkerAssessment::class, 'application_review_id');
    }

    public function supportAssessment()
    {
        return $this->hasOne(SupportAssessment::class, 'application_review_id');
    }
    public function committeeApprovals()
    {
        return $this->hasMany(CommitteeApproval::class);
    }

    public function approvedDecisions()
    {
        return $this->committeeApprovals()->where('status', 'approved')->orWhere('status', 'override')->get();
    }

    public function declinedDecisions()
    {
        return $this->committeeApprovals()->where('status', 'declined')->get();
    }

    public function hasReviewBy($user_id)
    {
        return $this->committeeApprovals()->where('committee_member_id', $user_id)->count();
    }
}
