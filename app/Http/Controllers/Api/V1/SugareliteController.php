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

            if(isset($input['user_id']))
            {
                $existingUser = User::where('id', '!=', $input['user_id'])->where('email', $input['email'])->first();
                if ($existingUser) {
                    return response()->json(['success'=> false,'error' => 'User already exists with this email.'], 422);
                }
            }
            else
            {
                $existingUser = User::where('email', $input['email'])->first();
                if ($existingUser) {
                    return response()->json(['success'=> false,'error' => 'User already exists with this email.'], 422);
                }
            }

            if($file = $request->file('avatar_url'))
            {
                $path = 'public/uploads/user/';
    
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move($path, $filename);
    
                $img = 'public/uploads/user/' . $filename;
                
                $input['avatar_url'] = $img;
    
                if(isset($input['user_id']) && $input['user_id']!= "")
                {
                    $getUserDetails = User::where('id', $input['user_id'])->first();
                    if ($getUserDetails) {
                        $proFilePath = $getUserDetails->avatar_url;
                        $proPath = substr(strstr($proFilePath, 'public/'), strlen('public/'));
    
                        if (file_exists(public_path($proPath))) {
                            \File::delete(public_path($proPath));
                        }
                    }
                }
            }

            $input['age'] = $age;
            $input['user_role'] = "user";
            $input['user_status'] = "active";

            $userArr = [];
            $userArr['username'] = $input['username'];
            $userArr['email'] = $input['email'];
            $userArr['sex'] = $input['sex'];
            $userArr['height'] = $input['height'];
            $userArr['premium'] = $input['premium'];
            $userArr['age'] = $age;
            $userArr['weight'] = $input['weight'];
            $userArr['country'] = $input['country'];
            $userArr['sugar_type'] = $input['sugar_type'];
            $userArr['birthdate'] = $input['birthdate'];
            $userArr['region'] = $input['region'];
            $userArr['bio'] = $input['bio'];
            $userArr['ethnicity'] = $input['ethnicity'];
            $userArr['body_structure'] = $input['body_structure'];
            $userArr['hair_color'] = $input['hair_color'];
            $userArr['piercings'] = $input['piercings'];
            $userArr['tattoos'] = $input['tattoos'];
            $userArr['education'] = $input['education'];
            $userArr['smoking'] = $input['smoking'];
            $userArr['drinks'] = $input['drinks'];
            $userArr['employment'] = $input['employment'];
            $userArr['civil_status'] = $input['civil_status'];
            $userArr['user_role'] = 'user';
            $userArr['user_status'] = 'active';
            // Create the user
            $messgae = '';
            if(isset($input['user_id']))
            {
                User::where('id', $input['user_id'])->update($userArr);
                $lastUserId = $input['user_id'];
                $messgae = 'User updated successfully.';
            }else{
                $userArr['password'] = $input['password'];
                $lastUserId = User::create($userArr);
                $lastUserId = $lastUserId->id;
                $messgae ='User registered successfully.';
            }
            
            if(!empty($request->file('public_images')))
            {
                foreach ($request->file('public_images') as $file)
                {
                    $path = 'public/uploads/user/public_images/';
    
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $file->move($path, $filename);
    
                    $img = 'public/uploads/user/public_images/' . $filename;
    
                    if(isset($input['user_id']) && $input['user_id']!= "")
                    {
                        $getUserDetails = User::where('id', $input['user_id'])->first();
                        if ($getUserDetails) {
                            $proFilePath = $getUserDetails->public_images;
                            $proPath = substr(strstr($proFilePath, 'public/'), strlen('public/'));
    
                            if (file_exists(public_path($proPath))) {
                                \File::delete(public_path($proPath));
                            }
                        }
                    }
    
                    $attachment['user_id'] = $lastUserId;
                    $attachment['public_images'] = $img;
                    $attachment['image_type'] = 'public';
                    User_images::create($attachment);
                }
            }
            if(!empty($request->file('total_private_images')))
        {
            foreach ($request->file('total_private_images') as $file)
            {
                $path = 'public/uploads/user/private_images/';

                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move($path, $filename);

                $img = 'public/uploads/user/private_images/' . $filename;

                if(isset($input['user_id']) && $input['user_id']!= "")
                {
                    $getUserDetails = User::where('id', $input['user_id'])->first();
                    if ($getUserDetails) {
                        $proFilePath = $getUserDetails->total_private_images;
                        $proPath = substr(strstr($proFilePath, 'public/'), strlen('public/'));

                        if (file_exists(public_path($proPath))) {
                            \File::delete(public_path($proPath));
                        }
                    }
                }

                $attachment['user_id'] = $lastUserId;
                $attachment['public_images'] = $img;
                $attachment['image_type'] = 'private';
                User_images::create($attachment);
            }
        }   
            $getUser = User::where('id', $lastUserId)->first();
            return response()->json(['success'=> true, 'message' => $messgae, 'user' => $getUser], 200);
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
        $sender_id = $request->input('sender_id');
        $receiver_id = $request->input('receiver_id');
        $message = $request->input('message');
        $type = $request->input('type');
        $id = $request->input('id') ?? '';
        $currentTimeMillis = round(microtime(true) * 1000);

        $senderCheck = User::where('id', $sender_id)->first();
        $recevierCheck = User::where('id', $receiver_id)->first();
        
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
                $profileList->avatar_url = $profileList->avatar_url ? url('/').'/'.$profileList->avatar_url : '';
                $profileList->age = $age;
                $response[] = $profileList;
                return response()->json(['success' => true, 'data' => $response]);
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
                $user->avatar_url = $user->avatar_url ? url('/').'/'.$user->avatar_url : '';
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

        $senderCheck = User::where('id', $input['sender_id'])->first();
        $recevierCheck = User::where('id', $input['receiver_id'])->first();

        if($senderCheck && $recevierCheck)
        {
            if(!$getfriend)
            {
                if(!$friends)
                {
                    Friend_list::create($input);
                    return response()->json(['success' => true, 'message' => 'Friend requrest already sent successfully']);
                }else{
                    if(isset($input['is_approved']) == 1)
                    {
                        $getfriend->update(['is_friend' => 1]);
                        return response()->json(['success' => true, 'message' => 'Both are Friends']);
                    }
                    Friend_list::create($input);
                    return response()->json(['success' => true, 'message' => 'Friend requrest sent successfully']);
                }
            }else{
                if(isset($input['is_approved']) == 1 && isset($getfriend))
                {
                    $getfriend->update(['is_friend' => 1]);
                    return response()->json(['success' => true, 'message' => 'Both are Friends']);
                }
                if(isset($input['is_approved']) == 1 && isset($friends))
                {
                    $friends->update(['is_friend' => 1]);
                    return response()->json(['success' => true, 'message' => 'Both are Friends']);
                }
                if(isset($getfriend) && $getfriend->is_friend == 1){
                    return response()->json(['success' => true, 'message' => 'Both are Friends']);
                }
                if(isset($getfriend) && $getfriend->is_friend == 1)
                {
                    return response()->json(['success' => true, 'message' => 'Both are Friends']);
                }
                return response()->json(['success' => true, 'message' => 'Friend requrest already sent successfully']);
            }
        }else{
            return response()->json(['success' => false, 'message' => 'User not exit']);
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
        
        foreach ($friendList as $key => $value) 
        {
            $value->is_private_album_access = '0';
            $checkFriend = Privatealbumaccess::where('receiver_id', $value->id)->where('sender_id', $input['id'])->where('status', 'approved')->first();
            if($checkFriend)
            {
                $value->is_private_album_access = '1';
            }
        }
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
        $sender_id = Privatealbumaccess::where('sender_id', $input['sender_id'])->where('receiver_id',$input['receiver_id'])->first();
        $receiver_id = Privatealbumaccess::where('receiver_id',$input['sender_id'])->where('sender_id',$input['receiver_id'])->first();

        $senderCheck = User::where('id', $input['sender_id'])->first();
        $recevierCheck = User::where('id', $input['receiver_id'])->first();

        if($senderCheck && $recevierCheck)
        {
            if(!$sender_id)
            {
                if(!$receiver_id)
                {
                    Privatealbumaccess::create($input);
                    return response()->json(['success' => true, 'message' => 'Private album access request sent successfully']);
                }else{
                    if(isset($input['is_approved']) == 1)
                    {
                        $sender_id->update(['status' => 'approved']);
                        return response()->json(['success' => true, 'message' => 'private album access granted']);
                    }
                    Privatealbumaccess::create($input);
                    return response()->json(['success' => true, 'message' => 'Private album access request sent successfully']);
                }
            }else{
                if(isset($input['is_approved']) == 1 && isset($sender_id))
                {
                    $sender_id->update(['status' => 'approved']);
                    return response()->json(['success' => true, 'message' => 'private album access granted']);
                }
                if(isset($input['is_approved']) == 1 && isset($receiver_id))
                {
                    $receiver_id->update(['status' => 'approved']);
                    return response()->json(['success' => true, 'message' => 'private album access granted']);
                }
                if(isset($sender_id) && $sender_id->status == 'approved'){
                    return response()->json(['success' => true, 'message' => 'private album access granted']);
                }
                if(isset($receiver_id) && $receiver_id->status == 'approved')
                {
                    return response()->json(['success' => true, 'message' => 'private album access granted']);
                }
                return response()->json(['success' => true, 'message' => 'Private album access already request sent successfully']);
            }
        }else{
            return response()->json(['success' => false, 'message' => 'User not exit']);
        }
    }
}