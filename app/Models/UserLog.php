<?php

namespace App\Models;

use App\Traits\tUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserLog extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'user_log';

    protected $fillable = [
        'ip',
        'ip_state',
        'ip_country',
        'device',
        'user_id'
    ];
}
