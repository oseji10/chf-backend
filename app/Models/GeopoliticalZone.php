<?php

namespace App\Models;

use App\Traits\tUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GeopoliticalZone extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'geopolitical_zone'
    ];

    protected $table = 'geopolitical_zone';

    public function states(){
        return $this->hasMany(State::class);
    }

    public function lgas(){
        return $this->hasManyThrough(LGA::class,State::class,'state_id','geopolitical_zone_id');
    }

    public function patients(){
        return $this->hasManyThrough(Patient::class,State::class);
    }

    public function residencePatients(){
        return $this->hasManyThrough(Patient::class,State::class,'geopolitical_zone_id','state_of_residence');
    }
}
