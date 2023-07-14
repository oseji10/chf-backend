<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StakeholderMarkup extends Model
{
    use HasFactory;

    protected $table = 'stakeholder_markup';

    protected $fillable = [
        'stakeholder_id',
        'markup',
        'is_active'
    ];

    public function stakeholder(){
        return $this->belongsTo(Stakeholder::class);
    }

    public function stakeholderTransactions(){
        return $this->hasMany(StakeholderTransaction::class,'stakeholder_transaction_id');
    }
}
