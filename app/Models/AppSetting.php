<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppSetting extends Model
{
    use HasFactory;

    protected $guard = 'app_setting';

    protected $fillable = [
        'key_name', 'value_txt'
    ];
}
