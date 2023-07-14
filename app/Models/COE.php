<?php

namespace App\Models;

use App\Traits\tUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class COE extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'coe';
    protected $primarykey = 'id';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'serial_number',
        'coe_name',
        'coe_type',
        'coe_address',
        'state_id',
        'coe_id_cap',
        'fund_allocation'
    ];

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function staffs()
    {
        return $this->hasMany(User::class, 'coe_id');
    }

    public function patientAppointments()
    {
        return $this->hasMany(PatientAppointment::class . 'coe_to_visit');
    }

    public function wallet()
    {
        return $this->hasOne(Wallet::class, 'coe_id');
    }

    public function billings()
    {
        return $this->hasMany(Transaction::class, 'coe_id');
    }

    /* CHANGE THIS RELATIONSHIP TO RETURN DISTINCT TRANSACTIONS */
    public function transactions()
    {
        return $this->billings()->select('coe_id')->distinct('transaction_id');
    }

    public function transactionsInterval($start_date, $end_date)
    {
        return $this->billings()->whereBetween('created_at', [$start_date, $end_date])->select('*')->groupBy('transaction_id');
    }

    /* TRANSACTIONS CAN EITHER BE DRUG OR SERVICE. IS_DRUG = 0 FOR SERVICES */
    public function filteredTransactionInterval($start_date, $end_date, $is_drug = 0)
    {
        return $this->billings()->where('is_drug', $is_drug)->whereBetween('created_at', $start_date, $end_date)->select('*')->distinct('transaction_id');
    }

    public function patients()
    {
        return $this->hasMany(Patient::class, 'coe_id');
    }

    public function stakeholderTransactions()
    {
        // return $this->hasManyThrough(StakeholderTransaction::class,)
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'coe_service', 'coe_id', 'service_id')->withPivot('price');
    }

    public function payments()
    {
        return $this->billings()->where('is_splitted', 1);
    }

    public function dispute()
    {
        return $this->hasOne(TransactionDispute::class, 'transaction_id', 'transaction_id');
    }
}
