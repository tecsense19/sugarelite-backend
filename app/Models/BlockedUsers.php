<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class BlockedUsers extends Model
{
    use HasFactory;

    protected $table = 'blocked_users';

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'is_blocked',
    ];   
}
