<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferralService extends Model
{
    use HasFactory;
    protected $table = 'referral_service';

    public function referral()
    {
        return $this->belongsTo(Referral::class, 'referral_id');
    }
}
