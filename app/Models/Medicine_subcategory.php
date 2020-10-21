<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Medicine_subcategory extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'medicine_category_id',
        'name',
        'description',
        'status',
    ];

    public function medicineCategory() {
        return $this->belongsTo('App\Models\Medicine_category', 'medicine_category_id');
    }
}
