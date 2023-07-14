<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class COEService extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'coe_service';

    protected $fillable=[
        'price',
        'coe_id',
        'service_id'
    ];
}
