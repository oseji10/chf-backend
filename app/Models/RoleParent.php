<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoleParent extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable=[
        'role_id',
        'parent_role_id',
    ];

    protected $table= 'role_parents';

    public function roleChild(){
        return $this->belongsTo(Role::class,'role_id');
    }

}
