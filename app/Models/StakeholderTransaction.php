<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StakeholderTransaction extends Model
{
    use HasFactory;

    protected $table = 'stakeholder_transaction';

    protected $fillable = [
        'transaction_id',
        'stakeholder_id',
        'stakeholder_markup_id',
        'amount',
        'is_paid'
    ];

    public function stakeholder(){
        return $this->belongsTo(Stakeholder::class);
    }

    public function markup(){
        return $this->belongsTo(StakeholderMarkup::class,'stakeholder_markup_id');
    }

    public function transaction(){
        return $this->belongsTo(Transaction::class);
    }
}
