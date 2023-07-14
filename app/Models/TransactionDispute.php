<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDispute extends Model
{
    use HasFactory;
    protected $table = 'transaction_dispute';

    protected $fillable = [
        'transaction_id',
        'coe_staff_id',
        'coe_id',
        'support_staff_id',
        'patient_user_id',
        'status',
        'disputed_by',
        'reason_for_dispute',
        'resolved_by',
        'resolved_on'
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'transaction_id', 'transaction_id');
    }

    public function coeStaff()
    {
        return $this->belongsTo(User::class, 'coe_staff_id')->select(['email', 'first_name', 'last_name', 'id']);
    }

    public function coe()
    {
        return $this->belongsTo(COE::class)->select(['coe_name', 'id']);
    }

    public function supportStaff()
    {
        return $this->belongsTo(User::class, 'support_staff_id')->select(['id', 'first_name', 'last_name', 'email']);
    }

    public function comments()
    {
        return $this->hasMany(TransactionDisputeComment::class);
    }

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_user_id')->select(['id', 'first_name', 'last_name', 'email']);
    }

    public function raiser()
    {
        return $this->belongsTo(User::class, 'disputed_by')->select(['id', 'first_name', 'last_name', 'email']);
    }
}
