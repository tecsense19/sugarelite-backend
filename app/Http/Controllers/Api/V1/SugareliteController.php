<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use App\Models\Reports;
use App\Models\User_images;
use App\Models\Messages;
use App\Models\Friend_list;
use App\Models\ChatImages;
use App\Models\BlockedUsers;
use App\Models\ContactUs;
use App\Models\Privatealbumaccess;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Intervention\Image\Facades\Image;
use Carbon\Carbon;
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
            $rules = [
                'username' => 'required',
                'country' => 'required',
                'sugar_type' => 'required',
                'birthdate' => 'required|date|before_or_equal:today',
                'email' => 'required',
                'region' => 'required',
            ];
            
            if (!isset($input['user_id'])) {
                $rules['password'] = 'required';
            }
            $validator = Validator::make($input, $rules);
            if ($validator->fails()) {
                return $this->sendError($validator->errors()->first());
            }
            if(isset($input['remove_images']))
            {
                $userImages = User_images::whereIn('id', explode(',', $input['remove_images']))->get();
                  foreach ($userImages as $key => $value) {
                    if ($value) {
                        $proFilePath = $value->public_images;
                        $proPath = substr(strstr($proFilePath, 'public/'), strlen('public/'));
            
                        if (file_exists(public_path($proPath))) {
                            \File::delete(public_path($proPath));
                        }
                    }
                  }     
                 User_images::whereIn('id', explode(',', $input['remove_images']))->delete();   
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
            $userArr = [];
            if($file = $request->file('avatar_url'))
            {
                $path = 'public/uploads/user/';
    
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move($path, $filename);
                $img = 'public/uploads/user/' . $filename;                
                $userArr['avatar_url'] = $img;
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
            $userArr['username'] = isset($input['username']) ? $input['username'] : '';
            $userArr['email'] = isset($input['email']) ? $input['email'] : '';
            $userArr['sex'] = isset($input['sex']) ? $input['sex'] : '';
            $userArr['height'] = isset($input['height']) ? $input['height'] : '';
            $userArr['premium'] = isset($input['premium']) ? $input['premium'] : '';
            $userArr['age'] = $age;
            $userArr['weight'] = isset($input['weight'])? $input['weight'] : '';
            $userArr['country'] = isset($input['country'])? $input['country'] : '';
            $userArr['sugar_type'] = isset($input['sugar_type'])? $input['sugar_type'] : '';
            $userArr['birthdate'] = isset($input['birthdate'])? $input['birthdate'] : '';
            $userArr['region'] = isset($input['region'])? $input['region'] : '';
            $userArr['bio'] = isset($input['bio'])? $input['bio'] : '';
            $userArr['ethnicity'] = isset($input['ethnicity'])? $input['ethnicity'] : '';
            $userArr['body_structure'] = isset($input['body_structure'])? $input['body_structure'] : '';
            $userArr['hair_color'] = isset($input['hair_color'])? $input['hair_color'] : '';
            $userArr['piercings'] = isset($input['piercings'])? $input['piercings'] : '';
            $userArr['tattoos'] = isset($input['tattoos'])? $input['tattoos'] : '';
            $userArr['education'] = isset($input['education'])? $input['education'] : '';
            $userArr['smoking'] = isset($input['smoking'])? $input['smoking'] : '';
            $userArr['drinks'] = isset($input['drinks'])? $input['drinks'] : '';
            $userArr['employment'] = isset($input['employment'])? $input['employment'] : '';
            $userArr['civil_status'] = isset($input['civil_status'])? $input['civil_status'] : '';
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

                    $attachment['user_id'] = $lastUserId;
                    $attachment['public_images'] = $img;
                    $attachment['image_type'] = 'private';
                    User_images::create($attachment);
                }
           }   
            $getUser = User::with('getAllProfileimg')->where('id', $lastUserId)->first();
            if($getUser)
            {
                $getUser->avatar_url = $getUser->avatar_url ? url('/').'/'.$getUser->avatar_url : '';
            }
            else{
                return $this->sendError('User not found.');
            }
            

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

    public function logout(Request $request)
    {
        try {
            $input = $request->all();
            // Get the authenticated user
            $user = User::where('id', $input['id'])->where('user_role', 'user')->first();
            if ($user) {
                // Update the user's online status to false
                $user->update([
                    'online' => 0,
                    'last_online' => now(), // Set last online to current timestamp
                ]);
                return $this->sendResponse('', 'Logged out successfully.');
            } else {
                return $this->sendError('User not found.');
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
            $getUser = User::with('getAllProfileimg')->where('email', $input['email'])->where('user_role', 'user')->first();
            if($getUser)
            {
                if($getUser->user_status == 'active'){
                    if(Hash::check($input['password'], $getUser->password))
                    {
                        $updateArry = [];
                        $updateArry['online'] = 1;
                        $updateArry['last_online'] = now();
                        User::where('email',$input['email'])->update($updateArry);
                        $getUser = User::with('getAllProfileimg')->where('email', $input['email'])->where('user_role', 'user')->first();
                        $getUser->avatar_url = $getUser->avatar_url ? url('/').'/'.$getUser->avatar_url : '';
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

                $user = User::where('id', $sender_id)->where('user_role', 'user')->first();
                $user->update([
                    'last_activity_at' => now(), // Set last online to current timestamp
                ]);
                if($user->is_subscribe == 0)
                {
                    // Assuming you have a method to count messages sent by the sender today
                    $messagesSentToday = $this->countMessagesSentToday($sender_id);
                    // Assuming you have a constant for the maximum messages allowed in free mode
                    $maxMessagesFreeMode = 3;
                    if ($messagesSentToday >= $maxMessagesFreeMode) {
                        return response()->json(['success' => false ,'message' => 'You have exceeded the daily message limit in free mode.']);
                    }
                }

                // Create a message entry without images
                $sendMessage = Messages::create($stringArr);
                $lastInsertedId = $sendMessage->id;
                if(!empty($request->file('chat_images'))) {
                    foreach ($request->file('chat_images') as $file) {
                        $path = 'public/uploads/user/public_images/';
                        $filename = time() . '_' . $file->getClientOriginalName();
                        $file->move($path, $filename);
                        $img = 'public/uploads/user/public_images/' . $filename;

                        // Store the image associated with the message
                        $attachment['user_id'] = $sender_id;
                        $attachment['chat_images'] = $img;
                        $attachment['message_id'] = $lastInsertedId; // Associate the image with the message
                        ChatImages::create($attachment);
                    }
                }
                $message = Messages::with('getAllChatimg')->where('id', $lastInsertedId)->first();
                return response()->json(['success' => true ,'message' => $message]);
            }
            else if($type == "edited")
            {
                $getMessage = Messages::where('id', $id)->first();
    
                    if ($getMessage) {
                        $remove_chatimages = $request->input('remove_chatimages');
                     
                        if($remove_chatimages)
                        {
                            $chatImages = ChatImages::whereIn('id', explode(',', $remove_chatimages))->get();
                         
                            foreach ($chatImages as $key => $value) {
                              if ($value) {
                                  $chatImgFilePath = $value->chat_images;
                                  $proPath = substr(strstr($chatImgFilePath, 'public/'), strlen('public/'));
                      
                                  if (file_exists(public_path($proPath))) {
                                      \File::delete(public_path($proPath));
                                  }
                              }
                            }    
                            ChatImages::whereIn('id', explode(',', $remove_chatimages))->delete();   
                        }
                        if(!empty($request->file('chat_images'))) {
                            foreach ($request->file('chat_images') as $file) {
                                $path = 'public/uploads/user/public_images/';
                                $filename = time() . '_' . $file->getClientOriginalName();
                                $file->move($path, $filename);
                                $img = 'public/uploads/user/public_images/' . $filename;
        
                                // Store the image associated with the message
                                $attachment['user_id'] = $sender_id;
                                $attachment['chat_images'] = $img;
                                $attachment['message_id'] = $id; // Associate the image with the message
                                ChatImages::create($attachment);
                            }
                        }
                        $newText = $request->input('message'); // Replace this with the updated text
                        $getMessage->update(['text' => $newText, 'type' => $type]);

                        $getMessageNew = Messages::with('getAllChatimg')->where('id', $id)->first();

                        return response()->json(['success' => true , 'message' => $getMessageNew]);
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

    public function countMessagesSentToday($sender_id)
    {
        // Logic to count the number of messages sent by the sender today
        // You would need to have a messages table with a sender_id and a timestamp column
        // Then you can count the number of messages sent by the sender today
        $messagesSentToday = Messages::where('sender_id', $sender_id)
                                    ->whereDate('created_at', Carbon::today())
                                    ->count();

        return $messagesSentToday;
    }

    public function messageList(Request $request)
    {
        $messageList = Messages::with('getAllChatWithImage')->get();
        return response()->json(['success' => true, 'data' => $messageList]);
    }

    public function profileList(Request $request)
    {
        $input = $request->all();

        if(isset($input['id']))
        {
            $profileList = User::where('id', $input['id'])->where('user_role', 'user')->with('getAllProfileimg')->first();

            if($profileList)
            {
                $birthdate = new DateTime($profileList['birthdate']);
                $currentDate = new DateTime();
                $age = $currentDate->diff($birthdate)->y;
                $profileList->avatar_url = $profileList->avatar_url ? url('/').'/'.$profileList->avatar_url : '';
                $profileList->age = $age;
                $profileList->allow_privateImage_access_users = Privatealbumaccess::where('receiver_id' , $input['id'])->where('status', '1')->get(['sender_id as user_id', 'updated_at as time']);
                $profileList->is_blocked_users = BlockedUsers::where('sender_id' , $input['id'])->where('is_blocked', 1)->get(['receiver_id as user_id', 'updated_at as time']);
                $profileList->reports = Reports::where('sender_id', $input['id'])->get(['receiver_id as user_id', 'updated_at as time']);
                $response[] = $profileList;
                return response()->json(['success' => true, 'data' => $response]);
            }else{
                return response()->json(['success' => false, 'data' => 'User not exits']);
            }
           
        }else{
            $profileList = User::with('getAllProfileimg')->where('user_role', 'user')->get();

            // Iterate through each user to calculate age
            $profileList->transform(function ($user) {
                // Assuming 'birthdate' is a property of the User model
                $birthdate = new DateTime($user->birthdate);
                $currentDate = new DateTime();
                $age = $currentDate->diff($birthdate)->y;
                // Add age to the user object
                $user->avatar_url = $user->avatar_url ? url('/').'/'.$user->avatar_url : '';
                $user->age = $age;
                $user->allow_privateImage_access_users = Privatealbumaccess::where('receiver_id' , $user['id'])->where('status', '1')->get(['sender_id as user_id', 'updated_at as time']);
                $user->is_blocked_users = BlockedUsers::where('sender_id' , $user['id'])->where('is_blocked', 1)->get(['receiver_id as user_id', 'updated_at as time']);
                $user->is_reports_users = Reports::where('sender_id' , $user['id'])->get(['receiver_id as user_id', 'updated_at as time']);
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
            $value->avatar_url = $value->avatar_url ? url('/').'/'.$value->avatar_url : '';
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
            'is_approved' => 'integer|required|in:0,1,2', // Add validation for status
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }

        // Check if the sender and receiver are valid users
        $senderCheck = User::find($input['sender_id']);
        $receiverCheck = User::find($input['receiver_id']);

        if (!$senderCheck || !$receiverCheck) {
            return $this->sendError('Sender or receiver does not exist');
        }

        // Create or update the record in the database
        $lastRequest = Privatealbumaccess::updateOrCreate(
            ['sender_id' => $input['sender_id'], 'receiver_id' => $input['receiver_id']],
            ['status' => $input['is_approved']]
        );
        
        $message = 'Request send successfully to '.($receiverCheck ? $receiverCheck->username : '');
        return response()->json(['success' => true ,'message' => $message, 'data' => $lastRequest], 201);
    }
    
    public function privateAlbumAcceptReject(Request $request)
    {
        $input = $request->all();
        
        $validator = Validator::make($input, [
            'request_id' => 'integer|required',
            'is_approved' => 'integer|required|in:0,1,2', // Add validation for status
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }
        
        $lastRequest = Privatealbumaccess::where('id', $input['request_id'])->update(['status'=> $input['is_approved']]);

        $findUser = Privatealbumaccess::where('id', $input['request_id'])->first();

        $senderCheck = User::find($findUser->sender_id);

        $message = "Access has been ".($input['is_approved'] == '1' ? 'granted to '.$senderCheck->username.'.' : 'decline to '.$senderCheck->username.'.');
        
        return response()->json(['success' => true ,'message' => $message, 'data' => $lastRequest], 201);
    }


    public function blockUser(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'sender_id' => 'integer|required',
            'receiver_id' => 'integer|required',
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }

        $senderId = $request->input('sender_id');
        $receiverId = $request->input('receiver_id');
        $isBlocked = $request->input('is_blocked');
    
        // Check if the receiver exists in the users table
        $receiverExists = User::where('id', $receiverId)->exists();
    
        if (!$receiverExists) {
            return response()->json(['success' => false ,'message' => 'User not found'], 404);
        }
    
          // Check if the sender and receiver are the same
        if ($senderId == $receiverId) {
            return response()->json(['success' => false , 'message' => 'Sender and receiver cannot be the same'], 400);
        }
        // Check if the record exists in blocked_users table
        $blockedUser = BlockedUsers::where('sender_id', $senderId)
                                  ->where('receiver_id', $receiverId)
                                  ->first();
    
        if ($blockedUser) {
            // If the record exists, update the is_blocked column
            if($isBlocked == 1)
            {
                $blockedUser->update(['is_blocked' => $isBlocked]);
                $blockedUser->refresh(); // Refresh the model to get updated values
                return response()->json(['success' => true , 'message' => 'User blocked', 'data' => $blockedUser]);
            }
            if($isBlocked == 0)
            {
                $blockedUser->update(['is_blocked' => $isBlocked]);
                $blockedUser->refresh(); // Refresh the model to get updated values
                return response()->json(['success' => true , 'message' => 'User unblocked', 'data' => $blockedUser]);
            }
           
        } else {
            // If the record doesn't exist, create a new one
            $blockedUser = BlockedUsers::create([
                'sender_id' => $senderId,
                'receiver_id' => $receiverId,
                'is_blocked' => $isBlocked,
            ]);
            return response()->json(['success' => true ,'message' => 'User blocked', 'data' => $blockedUser], 201);
        }
    }

    public function reportUser(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'sender_id' => 'integer|required',
            'receiver_id' => 'integer|required',
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }
        $senderId = $request->input('sender_id');
        $receiverId = $request->input('receiver_id');
        $newReason = $request->input('reason');
    
        // Check if the sender and receiver IDs are the same
        if ($senderId == $receiverId) {
            return response()->json(['success' => false, 'error' => 'Sender and receiver IDs cannot be the same'], 400);
        }
    
        // Check if the receiver exists in the users table
        $receiverExists = User::where('id', $receiverId)->exists();
        $senderIdExists = User::where('id', $senderId)->exists();

        if (!$receiverExists) {
            return response()->json(['success' => false ,'message' => 'User not found'], 404);
        }

        if (!$senderIdExists) {
            return response()->json(['success' => false ,'message' => 'User not found'], 404);
        }
        
        // Find the existing report
        $existingReport = Reports::where('sender_id', $senderId)
                                ->where('receiver_id', $receiverId)
                                ->first();
    
        if ($existingReport) {
            // If the report exists, update the reason
            $existingReport->reason = $newReason;
            $existingReport->save();
    
            return response()->json(['success' => true, 'message' => 'Report updated successfully', 'data' => $existingReport]);
        } else {
            // If the report doesn't exist, create a new one
            $createdAt = time(); // Assuming you're using UNIX timestamp for created_at
            $newReport = Reports::create([
                'sender_id' => $senderId,
                'receiver_id' => $receiverId,
                'reason' => $newReason,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
    
            return response()->json(['success' => true, 'message' => 'New report created', 'data' => $newReport], 201);
        }
    }
    public function push_notifications_private_album(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'user_id' => 'required'
        ]);
    
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }

        $push = Privatealbumaccess::where('receiver_id', $input['user_id'])->where('status', '0')->get();
        
        return $this->sendResponse($push, 'Private album pending records');
    }

    public function push_notifications_friend_request(Request $request)
    {
        $push = Friend_list::where('is_friend', 0)->get();
        return $this->sendResponse($push, 'Friend request pending records');
    }


    public function contactUs(Request $request)
    {
        try {
            $input = $request->all();

            $validator = Validator::make($input, [
                'email' => 'required'
            ]);
        
            if ($validator->fails()) {
                return $this->sendError($validator->errors()->first());
            }
                    $email = $input['email'];
                    $message = $input['message'];

                    $user_id = User::where('email', $email)->first();

                    if(!$user_id)
                    {
                        return $this->sendError('User not found.');
                    }
                    // Check if a record with the email exists
                    $existingRecord = ContactUs::where('email', $email)->first();
             
                    if (!$existingRecord) {
                        // Create a new record if it doesn't exist
                        
                        $newRecord = new ContactUs();
                        $newRecord->user_id = $user_id->id;
                        $newRecord->email = $email;
                        $newRecord->message = $message;
                        $newRecord->save();
                    } else {
                        // Update the existing record
                        $existingRecord->user_id = $user_id->id;
                        $existingRecord->message = $message;
                        $existingRecord->save();
                    }
                
                $ContactUs = ContactUs::where('email', $input['email'])->first();
                $respoArr['username'] = $user_id->username;
                $respoArr['email'] = $ContactUs->email;
                $respoArr['message'] = $ContactUs->message;
                $respoArr['logo_link'] = url('/').'/'.'public/assets/img/site-logo.png';

                Mail::send('mail/contactus', ['user' => $respoArr], function ($m) use ($respoArr, $input) {
                    $m->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
                    $m->to('gautam@tec-sense.com')->subject('Contact Us');
                });

                return $this->sendResponse($input['email'], 'Your message has been forwarded to our team. Thank you.');
            

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }



}