<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
// use Intervention\Image\Facades\Image;

use App\Http\Controllers\Api\BaseController as BaseController;

use Hash;
use Mail;
use Validator;

class SugareliteController extends BaseController
{
    public function register(Request $request)
    {
            $input = $request->all();

            $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $input['profile_image']));
            // echo '<pre>';print_r($imageData);echo '</pre>';die;
            $imageName = uniqid() . '.png';
            $image = Image::make(imagecreatefromstring($imageData));
            $modifiedImagePath = storage_path($imageName);
            $image->save($modifiedImagePath);
            $image->exif([]);
            $image->response('png');
            sleep(1);
            $imgUrl = env('APP_URL') ? env('APP_URL') . ('/storage'.'/'.$imageName) : url('/') . ('/storage'.'/'.$imageName);
            $input['profile_image'] = $imgUrl;
            User::create($input);

            
    }
}