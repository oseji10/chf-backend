<?php

namespace App\Models;

use App\Traits\tUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class COEWalletTopup extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'coe_wallet';

    protected $fillable = [
        'requester_id',
        'requested_on',
        
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

    public function coe(){
        return $this->belongsTo(COE::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'user_id', 'id');
    }
}
