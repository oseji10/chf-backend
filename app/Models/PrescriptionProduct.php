<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrescriptionProduct extends Model
{
    use HasFactory;

    protected $table = 'prescription_product';

    public $fillable = [
        'prescription_id',
        'drug_id',
        'dosage',
        'quantity_dispensed',
        'status'
    ];
}
