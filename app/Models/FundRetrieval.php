<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FundRetrieval extends Model
{
    use HasFactory;

    protected $table = 'fund_retrieval';

    protected $fillable = [
        'user_id',
        'wallet_balance',
        'amount_retrieved',
        'requested_by',
        'approved_by',
        'approved_on',
        'comment',
        'status',
        'coe_id',

    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->select(['id', 'first_name', 'last_name', 'email']);
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requested_by', 'id')->select(['id', 'first_name', 'last_name', 'email']);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by', 'id')->select(['id', 'first_name', 'last_name', 'email']);
    }

    public function coe()
    {
        return $this->belongsTo(COE::class, 'coe_id', 'id')->select(['id', 'coe_name']);
    }
}
