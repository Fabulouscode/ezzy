<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Medicine_category extends Model
{ 
    public $status_value = array(
        '0' => 'Active',
        '1' => 'Inactive',
    );
    use HasFactory, SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'status',
    ];

    protected $appends = ['status_name'];
    
    public function getStatusNameAttribute() {
        return array_key_exists($this->status, $this->status_value) ? $this->status_value[$this->status]: '';
    }

    public function medicinesubcategory() {
        return $this->hashOne('App\Models\Medicine_subcategory');
    }
}
