<?php

namespace App\Models;

use App\Traits\tUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'service_category';
    protected $fillable=[
        'category_name',
        'category_code',
    ];

    public function services(){
        return $this->hasMany(Service::class,'service_category_id');
    }

    public function coeService(){
        return $this->hasManyThrough(COEService::class, Service::class);
    }

    public function roles(){
        return $this->belongsToMany(Role::class,'role_service_category','service_category_id','role_id');
    }
}
