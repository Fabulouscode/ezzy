<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order_tracking extends Model
{
    use HasFactory;
    public $status_value = array(
            '0' => 'Order Placed',
            '1' => 'On the Way',
            '2' => 'Delivered',
            '3' => 'Cancel',
        );

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_id',
        'title',
        'description',
        'status',
    ];


    protected $appends = ['status_name'];

    public function getStatusNameAttribute() {
        return array_key_exists($this->status, $this->status_value) ? $this->status_value[$this->status]: '';
    }
}
