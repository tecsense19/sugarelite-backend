<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User_Report extends Model
{
    use HasFactory;

    protected $table = 'user_report';

    protected $fillable = [
        'user_id',
        'subscription',
        'payment_verification',
        'payment_recurring_date',
        'register_date',
    ]; 
    
    public function getUsers()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
