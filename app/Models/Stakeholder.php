<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stakeholder extends Model
{
    use HasFactory;

    protected $table = 'stakeholder';
    
    protected $fillable = [
        'stakeholder',
        'is_coe',
        'coe_id'
    ];

    public function markup(){
        return StakeholderMarkup::where('stakeholder_id',$this->id)->where('is_active',true)->orderBy('created_at','desc')->first();
    }
    
}
