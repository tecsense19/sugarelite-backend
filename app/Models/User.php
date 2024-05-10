<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;



class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username', 'user_role', 'avatar_url', 'sex', 'height', 'premium', 'age', 'weight', 'country', 'sugar_type', 'birthdate', 'email', 'mobile_no', 'verify_otp',  'password', 'region',  'bio', 'ethnicity', 'body_structure', 'hair_color', 'piercings', 'tattoos', 'education', 'smoking', 'drinks', 'employment', 'civil_status','user_status', 'confirmed_email', 'online', 'last_online', 'last_activity_at', 'created_at', 'updated_at', 'deleted_at','is_verified', 'subscription_stop_date', 
        'identity_file', 'is_identityverification', 'government_id_name' ,'subscription_resume_date'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function getUsersreport()
    {
        return $this->hasOne(User_Report::class, 'user_id', 'id');
    }

    public function getAllProfileimg()
    {
        return $this->hasMany(User_images::class, 'user_id', 'id');
    }

    // Define sender relationship
    public function sentFriendRequests() {
        return $this->hasMany(Friend_list::class, 'sender_id', 'id');
    }

    // Define receiver relationship
    public function receivedFriendRequests() {
        return $this->hasMany(Friend_list::class, 'receiver_id', 'id');
    }

    public function getLastSubscription()
    {
        return $this->hasOne(UserSubscription::class, 'user_id', 'id')->orderBy('id', 'desc');
    }

    public function getSubscriptionDetails()
    {
        return $this->hasMany(UserSubscription::class, 'user_id', 'id');
    }
}
