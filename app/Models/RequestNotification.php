<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class RequestNotification extends Model
{
    use HasFactory;

    protected $table = 'request_notification';

    protected $fillable = [
        'read_flag',
        'sender_id',
        'receiver_id',
    ];   
}
