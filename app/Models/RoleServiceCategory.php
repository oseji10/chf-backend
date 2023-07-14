<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoleServiceCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'role_service_category';

    protected $fillable=[
        'role_id',
        'service_category_id'
    ];

    
}
