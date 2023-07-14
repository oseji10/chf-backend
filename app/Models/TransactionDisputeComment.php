<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDisputeComment extends Model
{
    use HasFactory;

    protected $table = 'transaction_dispute_comment';

    protected $fillable = [
        'transaction_dispute_id',
        'user_id',
        'comment',
    ];


    public function user(){
        return $this->belongsTo(User::class);
    }

    public function transactionDispute(){
        return $this->belongsTo(TransactionDispute::class);
    }
}
