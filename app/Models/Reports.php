<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Reports extends Model
{
    use HasFactory;

    protected $table = 'reports';

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'reason',
    ];   
}
