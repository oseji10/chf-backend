<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MDTComment extends Model
{
    use HasFactory;

    protected $table = 'mdt_comment';

    protected $fillable = [
        'mdt_user_id',
        'patient_user_id',
        'comment',
        'visitation_date'
    ];

    public function mdtUser(){
        return $this->belongsTo(User::class, 'mdt_user_id');
    }

    public function patient(){
        return $this->belongsTo(User::class,'patient_user_id');
    }
}
