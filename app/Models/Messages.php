<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Messages extends Model
{
    use HasFactory;

    protected $table = 'message';

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'message_from',
        'message_to',
        'text',
        'type',
        'status',
        'milisecondtime',
        'created_at',
        'updated_at',
        'deleted_at',
    ];   

    public function getAllChatimg()
    {
        return $this->hasMany(ChatImages::class, 'user_id', 'sender_id');
    }

    public function getAllChatWithImage()
    {
        return $this->hasMany(ChatImages::class, 'message_id', 'id');
    }
}

