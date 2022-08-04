<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminActivity extends Model
{
    use HasFactory;
    protected $with=['admin'];

    protected $fillable= [
	    'admin_id', 'title', 'description', 'last_user_agent', 'last_ip_address','old_values','new_values'
    ];

    public function admin()
    {
        return $this->hasOne(Admin::class,'id','admin_id');
    }
}
