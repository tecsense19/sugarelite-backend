<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use App\Models\User_images;
use App\Models\Messages;
use App\Models\Friend_list;
use App\Models\Privatealbumaccess;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Intervention\Image\Facades\Image;
use DateTime;

use App\Http\Controllers\Api\BaseController as BaseController;

use Hash;
use Mail;
use Validator;

class SugareliteController extends BaseController
{
    public function register(Request $request)
    {
        try {

            $input = $request->all();
            // Assuming $input['birthdate'] contains the birthdate in the format 'YYYY/MM/DD'
            $birthdate = new DateTime($input['birthdate']);
            $currentDate = new DateTime();
            $age = $currentDate->diff($birthdate)->y;
            
            $validator = Validator::make($input, [
                'username' => 'required',
                'country' => 'required',
                'sugar_type' => 'required',
                'birthdate' => 'required|date|before_or_equal:today',
                'email' => 'required',
                'password' => 'required',
                'region' => 'required',
            ]);
        
            if ($validator->fails()) {
                return $this->sendError($validator->errors()->first());
            }

            
            $existingUser = User::where('email', $input['email'])->first();
            if ($existingUser) {
                return response()->json(['success'=> false,'error' => 'User already exists with this email.'], 422);
            }

            if ($files = $request->file('avatar_url')) {
                    $path = 'storage/app/public';
                    $filename = time() . '_' . $files->getClientOriginalName();
                    $files->move($path, $filename);
                    $img = 'storage/app/public/' . $filename;
                    $imgUrl = env('APP_URL') ? env('APP_URL') . ('/'.$img) : url('/') . ('/'.$img);
                    $input['avatar_url'] = $imgUrl;
            }
            $input['age'] = $age;
            $input['user_role'] = "user";
            $input['user_status'] = "active";
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
        
            return response()->json(['success'=> true, 'message' => 'User registered successfully.', 'user' => $user], 200);
            
            } catch (\Exception $e) {
                return $this->sendError($e->getMessage());
            }
    }

    public function checkUser(Request $request)
    {
        try {

            $input = $request->all();
            // Check if the email already exists in the database
            $validator = Validator::make($input, [
                'email' => 'required',
            ]);
        
            if ($validator->fails()) {
                return $this->sendError($validator->errors()->first());
            }
            $existingUser = User::where('email', $input['email'])->first();
            if ($existingUser) {
                return response()->json(['success'=> false,'message' => 'User already exists with this email.'], 422);
            }else{
                return response()->json(['success'=> true, 'message' => 'User not exists with this email.'], 200);
            }
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function login(Request $request)
    {
        try {

            $input = $request->all();

            $validator = Validator::make($input, [
                'email' => 'required',
                'password' => 'required',
            ]);
        
            if ($validator->fails()) {
                return $this->sendError($validator->errors()->first());
            }

            $getUser = User::where('email', $input['email'])->where('user_role', 'user')->first();

            if($getUser)
            {
                if($getUser->user_status == 'active'){
                    if(Hash::check($input['password'], $getUser->password))
                    {
                        return $this->sendResponse($getUser, 'Login Successfully.');
                    }
                    else
                    {
                        return $this->sendError('Invalid password! please try again.');
                    }
                }else{
                    return $this->sendError('User account deactivated');
                }
            }
            else
            {
                return $this->sendError('Invalid email address! please try again.');
            }

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function sendMessage(Request $request)
    {
        $sender_id = $request->input('message_from');
        $receiver_id = $request->input('message_to');
        $message = $request->input('message');
        $type = $request->input('type');
        $id = $request->input('id') ?? '';
        $currentTimeMillis = round(microtime(true) * 1000);

        $senderCheck = User::where('id', $sender_id)->first();
        $recevierCheck = User::where('id', $sender_id)->first();
        if($senderCheck && $recevierCheck)
        {
            $stringArr = [];
            $stringArr['sender_id'] = $sender_id;
            $stringArr['receiver_id'] = $receiver_id;
            $stringArr['text'] = $message;
            $stringArr['type'] = $type;
            $stringArr['milisecondtime'] = $currentTimeMillis;
    
            
            
            if($type == "regular"){
                $message = Messages::create($stringArr);
                $lastInsertedId = $message->id;
                return response()->json(['success' => true ,'message' => $message]);
            }
            else if($type == "edited")
            {
                $getMessage = Messages::where('id', $id)->first();
    
                    if ($getMessage) {
                        $newText = $request->input('message'); // Replace this with the updated text
                        $getMessage->update(['text' => $newText, 'type' => $type]);
                        return response()->json(['success' => true , 'message' => $getMessage]);
                    } else {
                        return response()->json(['success' => false , 'message' => 'message not found! Please enter message id']);
                    }                
                  
            }
            else if($type == "deleted" && $id != null)
            {
                $getMessage = Messages::where('id', $id)->first();
                if ($getMessage) {
                    $getMessage->update(['deleted_at' => 1]);
                    return response()->json(['success' => true, 'message' => 'Message deleted successfully']);
                } else {
                    return response()->json(['success' => false, 'message' => 'Message already deleted']);
                }      
            }else{
                return response()->json(['success' => false, 'message' => 'please added message type with message id']);
            }       
        }else{
            return response()->json(['success' => false, 'message' => 'User not exit']);
        }
       
    }

    public function messageList(Request $request)
    {
        $messageList = Messages::get();
        return response()->json(['success' => true, 'data' => $messageList]);
    }

    public function profileList(Request $request)
    {
        $input = $request->all();

        if(isset($input['id']))
        {
            $profileList = User::where('id', $input['id'])->with('getAllProfileimg')->first();
            if($profileList)
            {
                $birthdate = new DateTime($profileList['birthdate']);
                $currentDate = new DateTime();
                $age = $currentDate->diff($birthdate)->y;
                $profileList['age'] = $age;
                return response()->json(['success' => true, 'data' => $profileList]);
            }else{
                return response()->json(['success' => false, 'data' => 'User not exits']);
            }
           
        }else{
            $profileList = User::with('getAllProfileimg')->get();

            // Iterate through each user to calculate age
            $profileList->transform(function ($user) {
                // Assuming 'birthdate' is a property of the User model
                $birthdate = new DateTime($user->birthdate);
                $currentDate = new DateTime();
                $age = $currentDate->diff($birthdate)->y;
                
                // Add age to the user object
                $user->age = $age;
            
                return $user;
            });
            
            return response()->json(['success' => true, 'data' => $profileList]);
        }
    }

    public function forgotPassword(Request $request)
    {
        try {
            $input = $request->all();

            $validator = Validator::make($input, [
                'email' => 'required'
            ]);
        
            if ($validator->fails()) {
                return $this->sendError($validator->errors()->first());
            }

            $checkEmail = User::where('email', $input['email'])->where('user_role', 'user')->first();

            if($checkEmail)
            {
                $redToken = Str::random(8);

                $dataArr = [];
                $dataArr['forgot_pass_date'] = date('Y-m-d H:i:s');
                $dataArr['forgot_pass'] = 0;

                User::where('email', $input['email'])->update($dataArr);

                $respoArr['full_name'] = $checkEmail->username;
                $respoArr['pass_link'] = url('/').'/'.'forgot/password/view/'.Crypt::encryptString($checkEmail->id);
                $respoArr['logo_link'] = url('/').'/'.'public/assets/img/site-logo.png';

                Mail::send('mail/forgot_pass_mail', ['user' => $respoArr], function ($m) use ($respoArr, $input) {
                    $m->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));

                    $m->to( $input['email'] )->subject('Forgot Password');
                });

                return $this->sendResponse($input['email'], 'A password reset link has been sent to your email address. Please check your email for further instructions.');
            }
            else
            {
                return $this->sendError('Invalid user.');
            }

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function friend_list(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'sender_id' => 'integer|required',
            'receiver_id' => 'integer|required',
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }
        $getfriend = Friend_list::where('sender_id', $input['sender_id'])->where('receiver_id',$input['receiver_id'])->first();
        $friends = Friend_list::where('receiver_id',$input['sender_id'])->where('sender_id',$input['receiver_id'])->first();
        if(isset($getfriend)){
            if($getfriend->is_friend == 1)
            {
                return response()->json(['success' => true, 'message' => 'Both are Friends']);
            }else{
                return response()->json(['success' => true, 'message' => 'Friend requrest already sent successfully']);
            }
        }
        else if(isset($friends))
        {
            $friends->update(['is_friend' => 1]);
            return response()->json(['success' => true, 'message' => 'Both are Friends']);
        }
        else{
            Friend_list::create($input);
            return response()->json(['success' => true, 'message' => 'Friend requrest sent successfully']);
        }
    }

    public function friend_profile_list(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'id' => 'integer|required',
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }
      // Retrieve all friendships for the user
        $friendships = Friend_list::forUser($input['id'])->get();

        // Initialize an empty array to store friend IDs
        $friendIds = [];

        // Extract friend IDs from the friendships
        foreach ($friendships as $friendship) {
 
            // Add the sender ID if the user is the receiver, and vice versa
            if ($friendship->sender_id == $input['id']) {
                $friendIds[] = $friendship->receiver_id;
            } else {
                $friendIds[] = $friendship->sender_id;
            }
        }

        $friendList = User::whereIn('id', $friendIds)->with('getAllProfileimg')->get();
        return $this->sendResponse($friendList, 'success');
   
    }

    public function private_album(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'sender_id' => 'integer|required',
            'receiver_id' => 'integer|required',
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }
        $getfriend = Privatealbumaccess::where('sender_id', $input['sender_id'])->where('receiver_id',$input['receiver_id'])->first();
        $friends = Privatealbumaccess::where('receiver_id',$input['sender_id'])->where('sender_id',$input['receiver_id'])->first();
        if(isset($getfriend)){
            if($getfriend->status == 'approved')
            {
                return response()->json(['success' => true, 'message' => 'Private album access granted']);
            }else{
                return response()->json(['success' => true, 'message' => 'Private album access request already sent successfully']);
            }
        }
        else if(isset($friends))
        {
            $friends->update(['status' => 'approved']);
            return response()->json(['success' => true, 'message' => 'private album access granted']);
        }
        else{
            Privatealbumaccess::create($input);
            return response()->json(['success' => true, 'message' => 'Private album access request sent successfully']);
        }
    }
}