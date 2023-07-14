<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Referral extends Model
{
    use HasFactory;
    protected $table = 'referral';

    protected $appends = [
        'total',
    ];

    public function services()
    {
        return $this->hasMany(ReferralService::class, 'referral_id', 'id');
    }

    public function referrer()
    {
        return $this->belongsTo(User::class, 'referred_by');
    }

    public function referringCOE()
    {
        return $this->belongsTo(COE::class, 'referring_coe_id', 'id')->select(['coe_name', 'id']);
    }

    public function referenceCOE()
    {
        return $this->belongsTo(COE::class, 'reference_coe_id', 'id')->select(['coe_name', 'id']);
    }

    public function attendantStaff()
    {
        return $this->belongsTo(User::class, 'assigned_to', 'id')->select(['id', 'first_name', 'last_name', 'other_names', 'email']);
    }

    public function fulfiller()
    {
        return $this->belongsTo(User::class, 'fulfilled_by', 'id')->select(['id', 'first_name', 'last_name', 'other_names', 'email']);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_chf_id', 'chf_id');
    }

    public function getTotalAttribute()
    {
        return $this->services()->select(DB::raw("SUM(cost * quantity) as total"))->first()->total;
    }
}
