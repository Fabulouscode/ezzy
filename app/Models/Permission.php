<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'permission_category_id',
        'permission_title',
        'permission_name',
    ];

    public function hasPermissionTo($permission){
        return $this->hasOne('App\Models\Role_permission','id','permission_id')->where('permission_name' , $permission)->first();
    }
   
    public function getPermissionCategory(){
        return $this->hasOne('App\Models\Permission_category','id','permission_category_id');
    }
    
}
