<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatHistory extends Model
{
    use HasFactory;

    /**
     * The name of the "updated at" column.
     *
     * @var string
     */
    const UPDATED_AT = null;

    protected $connection = 'chat';

    protected $table = 'archive';

    protected $fillable = [
        'id',
        'username',
        'timestamp',
        'peer',
        'bare_peer',
        'xml',
        'txt',
        'chat_type',
        'kind',
        'nick',
        'created_at',
    ];

    // public function fromUser(){
    //     return $this->setConnection('mysql')->belongsTo('App\Models\User','from_id');
    // }

    // public function toUser(){
    //     return $this->setConnection('mysql')->belongsTo('App\Models\User','to_id');
    // }
}
