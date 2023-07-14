<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Patient;

class PatientTransferRequest extends Model
{
    use HasFactory;
    protected $table = 'patient_transfer_request';

    protected $fillable = [
        'patient_chf_id',
        'requesting_physician_id',
        'current_physician_id',
        'approved_by',
        'approved_on',
        'status',
        'reviewer_comment',
        'reason_for_transfer',
    ];

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_chf_id', 'chf_id');
    }

    public function requestingPhysician()
    {
        return $this->belongsTo(User::class, 'requesting_physician_id');
    }

    public function currentPhysician()
    {
        return $this->belongsTo(User::class, 'current_physician_id');
    }
}
