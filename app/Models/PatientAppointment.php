<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PatientAppointment extends Model
{
    use HasFactory, SoftDeletes;

    protected $table="patient_appointments";

    protected $fillable=[
        'user_id',
        'patient_id',
        'appointment_date',
        'appointment_time',
        'status',
        'is_confirmed',
        'coe_to_visit',
        'coe_staff_comment',
        'staff_id'
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class,'patient_id','id');
    }
    
    public function coe(){
        return $this->belongsTo(COE::class,'coe_to_visit','id');
    }

    public function staff(){
        return $this->belongsTo(User::class,'staff_id','id');
    }
}
