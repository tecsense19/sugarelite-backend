<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User_images extends Model
{
    use HasFactory;

    protected $table = 'user_images';

    protected $fillable = [
        'user_id',
        'public_images',
        'image_type',
    ];   
}
