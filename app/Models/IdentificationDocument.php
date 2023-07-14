<?php

namespace App\Models;

use App\Traits\tUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IdentificationDocument extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'identification_document';

    protected $filable = [
        'identification_type',
        'identification_description',
    ];
}
