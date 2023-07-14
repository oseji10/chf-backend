<?php

namespace App\Models;

use App\Traits\tUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasFactory, SoftDeletes;


    protected $fillable = [
        'service_name',
        'service_code',
        'parent_id',
        'created_by',
        'service_category_id',
        'price'
    ];


    protected $table = 'service';

    public function subServices()
    {
        return $this->hasMany(Service::class, 'parent_id');
    }

    public function billings()
    {
        return $this->hasMany(Transaction::class, 'service_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function category()
    {
        return $this->belongsTo(ServiceCategory::class, 'service_category_id');
    }

    public function parent()
    {
        return $this->belongsTo(Service::class, 'parent_id', 'id');
    }

    public function coes()
    {
        return $this->belongsToMany(COE::class, 'coe_service', 'service_id', 'coe_id')->withPivot('price')->select(['coe_name', 'id' => 'coe_id']);
    }
}
