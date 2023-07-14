<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommitteeApproval extends Model
{
    use HasFactory;
    protected $table = 'committee_approval';

    protected $fillable = [
        'status',
        'committee_member_id',
        'application_review_id',
        'reason',
    ];

    public function applicationReview(){
        return $this->belongsTo(ApplicationReview::class);
    }
}
