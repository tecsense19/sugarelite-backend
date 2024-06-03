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


     // Relationship to get the sender user
     public function sender()
     {
         return $this->belongsTo(User::class, 'sender_id', 'id');
     }
 
     // Relationship to get the receiver user
     public function receiver()
     {
         return $this->belongsTo(User::class, 'receiver_id', 'id');
     }

}
