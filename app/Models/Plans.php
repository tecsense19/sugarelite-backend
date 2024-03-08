<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plans extends Model
{
    use HasFactory;

    protected $table = 'plans';

    protected $fillable = [
        'test_product_id',
        'test_four_week_price_id',
        'test_twelve_week_price_id',
        'test_six_week_price_id',
        'live_product_id',
        'live_four_week_price_id',
        'live_twelve_week_price_id',
        'live_six_week_price_id',
        'test_or_live'
    ];   
}
