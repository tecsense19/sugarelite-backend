<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class UserElitesupport extends Model
{
    use HasFactory;

    protected $table = 'user_elitesupport';

    protected $fillable = [
        'title',
        'description',
    ];   

 
}
