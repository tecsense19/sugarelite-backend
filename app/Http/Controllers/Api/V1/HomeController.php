<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\BaseController as BaseController;
use DB;
use Validator;

use Twilio\Rest\Client;
use App\Services\FirebaseNotification;
// use App\Notifications\FirebaseNotification;

class HomeController extends BaseController
{
    /**
     * Constructor method
     */
    public function __construct()
    {
        // Initialize private variable in the constructor
        
    }
}

