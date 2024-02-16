<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Messages extends Model
{
    use HasFactory;

    protected $table = 'message';

    protected $fillable = [
        'user_id',
        'sender_id',
        'message_from',
        'message_to',
        'text',
        'milisecondtime',
        'created_at',
        'updated_at',
        'deleted_at',
    ];   
}
