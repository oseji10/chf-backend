<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UIMenu extends Model
{
    use HasFactory;

    protected $table = 'ui_menu';

    protected $fillable = [
        'menu_name',
        'menu_category',
        'menu_link',
        'menu_permission',
        'menu_parent_id',
        'menu_icon',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}
