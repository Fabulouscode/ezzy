<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'parent_id',
    ];

    public function categoryParent() {
        return $this->belongsTo('App\Models\Category', 'parent_id');
    }

    public function categoryChildren() {
        return $this->hasMany('App\Models\Category', 'parent_id')->orderBy('id', 'asc');
    }
}
