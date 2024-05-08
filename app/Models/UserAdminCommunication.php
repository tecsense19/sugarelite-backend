<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class UserAdminCommunication extends Model
{
    use HasFactory;

    protected $table = 'admin_communication';

    protected $fillable = [
        'user_id',
        'support_id',
    ];   

    public function getSupport()
    {
        return $this->hasOne(UserElitesupport::class, 'id', 'support_id');
    }
}
