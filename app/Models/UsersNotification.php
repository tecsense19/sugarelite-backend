<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class UsersNotification extends Model
{
    use HasFactory;

    protected $table = 'users_notification';

    protected $fillable = [
        'user_id',
        'sender_id',
        'message',
        'read_unread',
        'is_friend',
    ];   
}
