<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Static_pages extends Model
{
    use HasFactory;

    public $status_value = array(
        '0' => 'Active',
        '1' => 'Inactive',
    );
    
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'page_name',
        'page_description',
        'status',
    ];

    protected $appends = ['status_name'];

    public function getStatusNameAttribute() {
        return array_key_exists($this->status, $this->status_value) ? $this->status_value[$this->status]: '';
    }

}
