<?php
// app/Http/Controllers/CustomController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class CustomController extends Controller
{
    public function generatePassportKeys()
    {
        Artisan::call('passport:keys');
        return 'Passport keys generated.';
    }
}

