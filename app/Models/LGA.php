<?php

namespace App\Models;

use App\Traits\tUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LGA extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'lga';

    protected $fillable = [
        'lga',
        'state_id'
    ];

    public function state(){
        return $this->belongsTo(State::class);
    }
    

}
