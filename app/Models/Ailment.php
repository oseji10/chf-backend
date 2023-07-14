<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\tUUID;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ailment extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ailment';

    protected $fillable = [
        'ailment_type',
    ];

    public function patients(){
        return $this->hasMany(Patient::class);
    }
    
    public function patientsCount(){
        return $this->patients()->count();
    }
}
