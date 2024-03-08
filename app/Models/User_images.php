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

    // Define the accessor for the profile image path
    public function getPublicImagesAttribute()
    {
        // Assuming your profile image is stored in a column named 'profile_image'
        $profileImage = $this->attributes['public_images'];

        // Check if the profile image exists
        if ($profileImage) {
            // Assuming the profile images are stored in the 'storage/app/public' directory
            return url('/').'/'. $profileImage;
        }

        // If no profile image is set, return a default image path or null
        // You can customize this as per your application requirements
        return '';
    }
}
