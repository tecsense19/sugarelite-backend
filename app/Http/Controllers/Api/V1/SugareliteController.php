<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use App\Models\User_images;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Models\Messages;
use Intervention\Image\Facades\Image;

use App\Http\Controllers\Api\BaseController as BaseController;

use Hash;
use Mail;
use Validator;

class SugareliteController extends BaseController
{
    public function register(Request $request)
    {
        $input = $request->all();
        // Check if the email already exists in the database
        $existingUser = User::where('email', $input['email'])->first();
        if ($existingUser) {
            return response()->json(['error' => 'User already exists with this email.'], 422);
        }

        if ($files = $request->file('avatar_url')) {
                $path = 'storage/app/public';
                $filename = time() . '_' . $files->getClientOriginalName();
                $files->move($path, $filename);
                $img = 'storage/app/public/' . $filename;
        }
        // Proceed with user registration if email is unique
        // $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $input['avatar_url']));
        // $imageName = uniqid() . '.png';
        // $image = Image::make(imagecreatefromstring($imageData));
        // $modifiedImagePath = storage_path($imageName);
        // $image->save($modifiedImagePath);
        // $image->exif([]);
        // $image->response('png');
        //$saveimage = "storage/app/public/img".uniqid().".png";
        //file_put_contents($saveimage, $imageData);
        sleep(1);
        $imgUrl = env('APP_URL') ? env('APP_URL') . ('/'.$img) : url('/') . ('/'.$img);
        $input['avatar_url'] = $imgUrl;
        // Create the user
        $user = User::create($input);

        if (!empty($request->hasFile('public_images'))) {
            $path = 'storage/app/public';
        
            foreach ($request->file('public_images') as $file) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move($path, $filename);
        
                $img = 'storage/app/public/' . $filename;
                $imgUrl = env('APP_URL') ? env('APP_URL') . ('/'.$img) : url('/') . ('/'.$img);
                // Store the array of new file paths in the database or perform other actions
                $attachment['user_id'] = $user->id;
                $attachment['public_images'] = $imgUrl;
                $attachment['image_type'] = 'public';
                User_images::create($attachment);
            }
        }
        if (!empty($request->hasFile('total_private_images'))) {
            $path = 'storage/app/public';
        
            foreach ($request->file('total_private_images') as $file) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move($path, $filename);
        
                $img = 'storage/app/public/' . $filename;
                $imgUrl = env('APP_URL') ? env('APP_URL') . ('/'.$img) : url('/') . ('/'.$img);
                // Store the array of new file paths in the database or perform other actions
                $attachment['user_id'] = $user->id;
                $attachment['public_images'] = $imgUrl;
                $attachment['image_type'] = 'private';
                User_images::create($attachment);
            }
        }
    
        return response()->json(['message' => 'User registered successfully.', 'user' => $user], 200);
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