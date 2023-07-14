<?php

namespace App\Models;

use App\Traits\tUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Wallet extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'wallet';

    protected $fillable = [
        'balance',
        'is_coe',
        'coe_id'
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
