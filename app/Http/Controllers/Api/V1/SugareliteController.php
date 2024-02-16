<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Models\Messages;
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

    public function sendMessage(Request $request)
    {
        $user_id = $request->input('message_from');
        $sender_id = $request->input('message_to');
        $message = $request->input('message');
        $currentTimeMillis = round(microtime(true) * 1000);

        $stringArr = [];
        $stringArr['user_id'] = $user_id;
        $stringArr['sender_id'] = $sender_id;
        $stringArr['message_from'] = $user_id;
        $stringArr['message_to'] = $sender_id;
        $stringArr['text'] = $message;
        $stringArr['milisecondtime'] = $currentTimeMillis;

        $data = [
            'user_id' => $user_id,
            'sender_id' => $sender_id,
            'message_from' => $user_id,
            'message_to' => $sender_id,
            'message' => $message,
            'milisecondtime' => $currentTimeMillis,
        ];
        
        $endpoint = env('APP_URL').'/chat/webhook/message?' . http_build_query($data);
        
        $ch = curl_init($endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPGET, true);
        
        $response = curl_exec($ch);
        
        if ($response === false) {
            echo 'Error: ' . curl_error($ch);
        }
        
        curl_close($ch);
        Messages::create($stringArr);
        return response()->json(['status' => 'true' , 'message_from' => $user_id, 'message_to' => $sender_id, 'message' => $message , 'milisecondtime' => $currentTimeMillis]);
    }

    public function messageList(Request $request)
    {
        $messageList = Messages::get();
        return response()->json(['status' => 'true', 'data' => $messageList]);
    }
}