<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'comment',
        'transaction_id',
        'commented_by'
    ];

    protected $table = 'comment';

    public function transaction(){
        return $this->belongsTo(Transaction::class);
    }

    public function commentedBy(){
        return $this->belongsTo(User::class,'commented_by');
    }

    public function user(){
        return $this->hasOneThrough(User::class,Transaction::class,'transaction_id','id','transaction_id','user_id');
    }

    public function documents(){
        return $this->hasManyThrough(TransactionDocument::class,Transaction::class,'transaction_id','transaction_id','transaction_id','transaction_id');
    }
}
