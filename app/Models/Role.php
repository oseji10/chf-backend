<?php

namespace App\Models;

use App\Traits\tUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'role',
        'description',
    ];

    protected $table = 'role';

    public function users(){
        return $this->belongsToMany(User::class,'role_user','role_id','user_id');
    }

    public function permissions(){
        return $this->belongsToMany(Permission::class,'permission_role','role_id','permission_id');
    }
    
    public function roleParents(){
        return $this->hasMany(RoleParent::class,'role_id','id');
    }

    public function serviceCategories(){
        return $this->belongsToMany(ServiceCategory::class,'role_service_category','role_id','service_category_id');
    }

    public function hasPermission($permission_id){
        return $this->permissions->where('id',$permission_id)->count();
    }
}
