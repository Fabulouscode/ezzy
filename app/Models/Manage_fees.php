<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manage_fees extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category_id',
        'fees_percentage',
        'fees_name',
        'fees_key',
        'fees_type'
    ];

    public function category() {
        return $this->belongsTo('App\Models\Category','category_id','id');
    }
}
