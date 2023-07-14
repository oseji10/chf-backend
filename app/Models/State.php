<?php

namespace App\Models;

use App\Traits\tUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class State extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'state';

    protected $fillable = [
        'id',
        'state',
        'geopolitical_zone_id'
    ];

    public function coes(){
        return $this->hasMany(COE::class,"state_id");
    }

    public function geopoliticalZone(){
        return $this->belongsTo(GeopoliticalZone::class,'geopolitical_zone_id');
    }


    public function lgas(){
        return $this->hasMany(LGA::class,"state_id");
    }

    public function lga($lgaid){
        return $this->lgas()->where('id',$lgaid)->first();
    }

    public function patients(){
        return $this->hasMany(Patient::class,'state_id');

    }

    public function residencePatients(){
        return $this->hasMany(Patient::class,'state_of_residence');

    }
}
