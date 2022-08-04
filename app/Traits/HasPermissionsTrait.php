<?php

namespace App\Traits;

use App\Models\Permission;
use App\Models\Role;

trait HasPermissionsTrait {

  public function hasMultiplePermissionTo(... $permissions) {
    foreach ($permissions as $key => $value) {
      if ($this->hasPermissionTo(strtolower($value))) {
        return true;
      }
    }     
    return false;
  }

  public function hasPermissionTo($permission) {
    foreach ($this->roles->permissions as $key => $value) {
      if (strtolower($value->permission_name) == strtolower($permission)) {
        return true;
      }
    }     
    return false;
  }

  public function hasRole($role) {
    if (strtolower($this->roles->role_name) ==  strtolower($role)) {
      return true;
    }
    return false;
  }

  public function roles() {
    return $this->hasOne('App\Models\Role', 'id', 'role_id');
  }


  public function permissions() {
    return $this->belongsToMany('App\Models\Permission','role_permissions');
  }

}
