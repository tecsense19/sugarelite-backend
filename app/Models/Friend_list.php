<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Friend_list extends Model
{
    use HasFactory;

    protected $table = 'friend_list';

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'is_friend',
    ];   

    public function scopeForUser($query, $userId)
    {
        return $query->where(function (Builder $q) use ($userId) {
            $q->where('is_friend',1)->where('sender_id', $userId)
                ->orWhere('receiver_id', $userId);
        });
    }
}
