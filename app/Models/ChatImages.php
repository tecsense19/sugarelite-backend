<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class ChatImages extends Model
{
    use HasFactory;

    protected $table = 'chat_images';

    protected $fillable = [
        'user_id',
        'chat_images',
        'message_id',
    ];   

    public function getChatImagesAttribute()
    {
        // Assuming your profile image is stored in a column named 'profile_image'
        $chatImage = $this->attributes['chat_images'];

        // Check if the profile image exists
        if ($chatImage) {
            // Assuming the profile images are stored in the 'storage/app/public' directory
            return url('/').'/'. $chatImage;
        }

        // If no profile image is set, return a default image path or null
        // You can customize this as per your application requirements
        return '';
    }
}
