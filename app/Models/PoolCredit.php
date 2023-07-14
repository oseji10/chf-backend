<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PoolCredit extends Model
{
    use HasFactory;

    protected $table = 'pool_credit';

    protected $fillable = [
        'benefactor',
        'credit',
        'user_id',
    ];
}
