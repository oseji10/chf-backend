<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDocument extends Model
{
    use HasFactory;

    protected $table = 'transaction_document';

    protected $fillable = [
        'id',
        'document_url',
        'document_name',
        'transaction_id'
    ];
}
