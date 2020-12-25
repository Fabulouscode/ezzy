<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat_history extends Model
{
    use HasFactory;

    public $chat_type_value = array(
        '0' => 'ePrescibe',
        '1' => 'eRecommendation',
        '2' => 'eDiagnostics',
        '3' => 'Treatment Plan'
    );

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'client_id',
        'chat_type',
        'plan_name',
        'treatment_name',
    ];

    protected $appends = ['chat_type_name'];

    public function getChatTypeNameAttribute() {
        return array_key_exists($this->chat_type, $this->chat_type_value) ? $this->chat_type_value[$this->chat_type]: '';
    }
}
