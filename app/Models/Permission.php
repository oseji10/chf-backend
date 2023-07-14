<?php

namespace App\Models;

use App\Traits\tUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'permission',
        'description',
        'permission_id'
    ];

    protected $table = 'permission';

    public function roles(){
        return $this->belongsToMany(Role::class,'permission_role','permission_id','role_id');
    }

    public function users(){
        return $this->belongsToMany(User::class,'permission_user','permission_id','user_id');
    }
}
