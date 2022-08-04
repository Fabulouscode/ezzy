<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppVersion extends Model
{
    use HasFactory;

    protected $guard = 'admin';

    protected $fillable = [
        'android_version', 'ios_version'
    ];

}
