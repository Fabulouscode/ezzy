<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order_tracking extends Model
{
    use HasFactory;
    public $status_value = array(
            '0' => 'Order Placed',
            '1' => 'Order Accepted',
            '2' => 'Order On the Way',
            '3' => 'Order Delivered',
            '4' => 'Order Cancel',
            '5' => 'Order Completed',
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
        'estimation_datetime',
        'status',
    ];


    protected $appends = ['status_name'];

    public function getStatusNameAttribute() {
        return array_key_exists($this->status, $this->status_value) ? $this->status_value[$this->status]: '';
    }
}
