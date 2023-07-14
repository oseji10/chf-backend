<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NextOfKin extends Model
{
    use HasFactory;

    protected $table="next_of_kin";

    protected $fillable=[
        'patient_id',
        'user_id',
        'name',
        'relationship',
        'phone_number',
        'alternate_phone_number',
        'email',
        'address',
        'city',
        'state_of_residence',
        'lga_of_residence',
        'created_at',
        'updated_at',
    ];
    
    public function patient()
    {
        return $this->belongsTo(Patient::class,'patient_id','id');
    }

    public function state(){
        return $this->belongsTo(State::class);
    }

    public function stateOfResidence(){
        return $this->hasOne(State::class,'id','state_of_residence');
    }
}
