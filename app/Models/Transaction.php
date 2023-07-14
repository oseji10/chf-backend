<?php

namespace App\Models;

use App\Traits\tUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'transaction';

    protected $fillable = [
        'transaction_id',
        'biller_id',
        'service_id',
        'quantity',
        'cost',
        'discount',
        'total',
        'coe_id',
        'user_id',
        'is_drug',
        'drug_id',
        'is_splitted',
        'status',
        'payment_initiated_by',
        'payment_initiated_on',
        'payment_recommended_by',
        'payment_recommended_on',
        'payment_approved_by',
        'payment_approved_on',
        'cmd_approver_id',
        'cmd_approved_on',
        'is_disputed',
        'prescription_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function coe()
    {
        return $this->belongsTo(COE::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id', 'id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'transaction_id', 'transaction_id');
    }

    public function paymentTransactions()
    {
        return $this->hasMany(Transaction::class, 'payment_initiated_on', 'payment_initiated_on');
    }

    public function biller()
    {
        return $this->belongsTo(User::class, 'biller_id');
    }

    public function comment()
    {
        return $this->hasOne(Comment::class, 'transaction_id', 'transaction_id');
    }

    public function stakeholderTransactions()
    {
        return $this->hasMany(StakeholderTransaction::class, 'transaction_id');
    }

    public function documents()
    {
        return $this->hasMany(TransactionDocument::class, 'transaction_id', 'transaction_id');
    }

    public function dispute()
    {
        return $this->hasOne(TransactionDispute::class, 'transaction_id', 'transaction_id');
    }

    /* F AND A PAYMENTS */
    public function payment()
    {
        return $this->belongsTo(Payment::class, 'payment_initiated_on', 'payment_initiated_on');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'payment_approved_by');
    }

    public function recommendedBy()
    {
        return $this->belongsTo(User::class, 'payment_recommended_by');
    }

    public function initiatedBy()
    {
        return $this->belongsTo(User::class, 'payment_initiated_by');
    }

    public function dfaPaymentApprovedBy()
    {
        return $this->belongsTo(User::class, 'dfa_id');
    }

    public function permSecPaymentApprovedBy()
    {
        return $this->belongsTo(User::class, 'permsec_id');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'user_id', 'user_id');
    }
}
