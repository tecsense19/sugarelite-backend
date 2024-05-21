<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LanguageMaster extends Model
{
    use HasFactory;

    protected $table = 'language_master';

    protected $fillable = [
        'id',
        'var_string',
        'english_string',
        'danish_string'
    ];   
}
