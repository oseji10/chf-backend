<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/* REIMBURSEMENT TO COEs FOR SERVICES RENDERED. */

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payment';

    protected $fillable = [
        'payment_reference',
        'payment_initiated_on',
        'payment_initiated_by',
        'payment_recommended_on',
        'payment_recommended_by',
        'payment_approved_on',
        'payment_approved_by',
        'payment_amount',
        'status',
        'start_date',
        'end_date',
        'coe_id',
    ];

    protected $appends = [
        'computed_total'
    ];

    public function coe(){
        return $this->belongsTo(COE::class, 'coe_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'cmd_approved_on', 'cmd_approved_on');
    }

    public function cmd()
    {
        return $this->belongsTo(User::class, 'cmd_approver_id', 'id');
    }

    public function dfa()
    {
        return $this->belongsTo(User::class, 'dfa_id', 'id');
    }

    public function permsec()
    {
        return $this->belongsTo(User::class, 'permsec_id', 'id');
    }

    public function initiator()
    {
        return $this->belongsTo(User::class, 'payment_initiated_by', 'id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'payment_approved_by', 'id');
    }

    public function recommender()
    {
        return $this->belongsTo(User::class, 'payment_recommended_by', 'id');
    }

    public function getComputedTotalAttribute()
    {
        return $this->transactions()->sum(DB::raw('cost * quantity'));
    }
}
