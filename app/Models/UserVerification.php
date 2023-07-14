<?php

namespace App\Models;

use App\Traits\tUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserVerification extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = 'user_verification';

    protected $fillable = [
        'channel',
        'type',
        'hash',
        'status',
    ];
}
