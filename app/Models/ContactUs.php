<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class ContactUs extends Model
{
    use HasFactory;

    protected $table = 'contactus';

    protected $fillable = [
        'user_id',
        'email',
        'message',
    ];   
}
