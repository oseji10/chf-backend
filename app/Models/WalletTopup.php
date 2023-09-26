<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletTopup extends Model
{
    use HasFactory;
    protected $table = 'wallet_topup';

    protected $fillable = [
        'requester_id',
        'requested_on',
        'patient_user_id',
        'approver_id',
        'approved_on',
        'credited_by',
        'credited_on',
        'previous_balance',
        'requester_comment',
        'coe_id',
        'amount_requested',
        'amount_credited',
        'status'

    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'patient_user_id', 'id');
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requester_id', 'id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id', 'id');
    }

    public function creditor()
    {
        return $this->belongsTo(User::class, 'credited_by', 'id');
    }

    public function patient()
    {
        return $this->hasOne(Patient::class, 'user_id', 'patient_user_id');
    }

    public function coe(){
        return $this->belongsTo(COE::class);
    }

    public function wallet()
    {
        return $this->hasOne(Wallet::class, 'user_id', 'patient_user_id');
    }
}
