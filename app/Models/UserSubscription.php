<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSubscription extends Model
{
    use HasFactory;

    protected $table = 'user_subscription';

    protected $fillable = [
        'user_id',
        'price_id',
        'plan_type',
        'plan_price'
    ];   
}
