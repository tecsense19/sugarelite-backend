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
use App\Models\UserSubscription;
use App\Models\RequestNotification;
use App\Models\UsersNotification;
use App\Models\UserAdminCommunication;
use App\Models\UserElitesupport;
use App\Models\LanguageMaster;
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
    public function MobileEmailOtp(Request $request)
    {
        $input = $request->all();
        $rules = [
            'contact_info' => function ($attribute, $value, $fail) use ($input) {
                $emailProvided = isset($input['email']) && $input['email'] !== '';
                $mobileNoProvided = isset($input['mobile_no']) && $input['mobile_no'] !== '';
        
                if ($emailProvided && $mobileNoProvided) {
                    $fail('Provide either email or mobile number, not both.');
                } elseif (!$emailProvided && !$mobileNoProvided) {
                    $fail('Please provide either email or mobile number.');
                }
            },
            'email' => 'nullable|email', // This rule checks if the email is a valid format
            // 'mobile_no' => 'nullable|digits:12', // This rule checks if the mobile number has exactly 10 digits
        ];
        

          $validator = Validator::make($input, $rules);
            if ($validator->fails()) {
                return $this->sendError($validator->errors()->first());
            }

            //$otp = mt_rand(100000, 999999);
            $otp = 654321;
            $userArr = [];
            $userArr['email'] = isset($input['email']) ? $input['email'] : '';
            $userArr['mobile_no'] = isset($input['mobile_no']) ? $input['mobile_no'] : '';
            $userArr['verify_otp'] = isset($otp) ? $otp : '';

            if(isset($input['email']) && $input['email'])
            {
                 // Email content
                 $emailContent = "Your OTP code is: " . $otp;
                try {
                // Mail::send('mail/otp', ['emailContent' => $emailContent], function ($m) use ($emailContent, $input) {
                //     $m->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
                //         $m->to( $input['email'] )->subject('OTP verification');
                //     });
                    
                } catch (\Exception $e) {
                    return response()->json(['error' => 'Failed to send OTP email. Please try again later.'.$e], 500);
              }
                 
                $data_email_no = User::where('email', $input['email'])->first();
                if ($data_email_no) {
                    User::where('email', $input['email'])->update($userArr);
                    $data = [
                        'id' => $data_email_no->id
                    ];
                    return response()->json(['success'=> false, 'data' => $data,'message' => 'User already exists with this email.'], 200);
                }
                
                if(!$data_email_no)
                {
                    $lastUserId = User::create($userArr);
                    $messgae ='Otp sent successfully on your email address';
                }
                
            }else{
                $otp = 123456;
                $userArr['verify_otp'] = isset($otp) ? $otp : '';
                $data_mobile_no = User::where('mobile_no', $input['mobile_no'])->first();
                if ($data_mobile_no) {
                    User::where('mobile_no', $input['mobile_no'])->update($userArr);
                    $data = [
                        'id' => $data_mobile_no->id
                    ];
                    return response()->json(['success'=> false,'data' => $data,'message' => 'User already exists with this mobile_no.'], 200);
                }
            
                if(!$data_mobile_no)
                {
                    $lastUserId = User::create($userArr);
                    $messgae ='Otp sent successfully on your mobile number';
                }else{
                    User::where('mobile_no', $input['mobile_no'])->update($userArr);
                    $messgae = 'Otp sent successfully on your mobile number';
                }
    
            }

                $output = User::where('id', $lastUserId->id)->first();

                $output_cut = [
                    'id' => $output->id
                ];
            
            return response()->json(['success'=> true, 'data' => $output_cut,'message' => $messgae], 200);       
    }

    public function MobileEmailVerifyOtp(Request $request)
    {
        $input = $request->all();
        $rules = [
            
            'id' => 'integer', // This rule checks if the email is a valid format
            'otp' => 'nullable|digits:6', // This rule checks if the mobile number has exactly 10 digits
        ];

        $Otp_check = User::where('id', $input['id'])->first();

        if(isset($input['otp']) && $Otp_check->verify_otp == $input['otp'])
        {
            $userArr['is_verified'] = 1;
            User::where('id', $input['id'])->update($userArr);
            return response()->json(['success'=> true,'message' => 'OTP Verification success'], 200);  
        }else{
            return response()->json(['success'=> false,'error' => 'Please enter a valid OTP!'], 422);  
        }
    }



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
                'region' => 'required',
            ];
            
            if (!isset($input['user_id'])) {
                $rules['password'] = 'required';
            }
            $validator = Validator::make($input, $rules);
            if ($validator->fails()) {
                return $this->sendError($validator->errors()->first());
            }

            $oneQuery = User::where('id', $input['user_id'])->where('is_verified', 0)->first();
            if($oneQuery)
            {
                return $this->sendError('Please verify your email');
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
            $userArr['mobile_no'] = isset($input['mobile_no'])? $input['mobile_no'] : '';            
            $userArr['user_role'] = 'user';
            $userArr['user_status'] = 'active';

            // Create the user
            $messgae = '';
            if(isset($input['user_id']))
            {                
                $lastUserId = $input['user_id'];
                if(isset($input['otp']))
                {
                    if(isset($input['email']) || isset($input['mobile_no']))
                    {
                        $query = User::where(function($query) use ($input) {
                            if(isset($input['email'])) {
                                $query->where('email', $input['email']);
                            }
                            
                            if(isset($input['mobile_no'])) {
                                $query->orWhere('mobile_no', $input['mobile_no']);
                            }
                        })
                        ->where('id', '!=', $input['user_id'])
                        ->first();

                        if($query) {
                            if(isset($input['email']) && $query->email === $input['email']) {
                                return $this->sendError('Email address already exists.');
                            } elseif(isset($input['mobile_no']) && $query->mobile_no === $input['mobile_no']) {
                                return $this->sendError('Mobile number already exists.');
                            }
                        }
                    }      
                        $userArr['password'] = Hash::make($input['password'] ?? '');
                        User::where('id', $input['user_id'])->update($userArr);
                        $messgae = 'User Register successfully.';
                       
                }else{

                    if(isset($input['email']) || isset($input['mobile_no']))
                    {
                        $query = User::where(function($query) use ($input) {
                            if(isset($input['email'])) {
                                $query->where('email', $input['email']);
                            }
                            
                            if(isset($input['mobile_no'])) {
                                $query->orWhere('mobile_no', $input['mobile_no']);
                            }
                        })
                        ->where('id', '!=', $input['user_id'])
                        ->first();

                        if($query) {
                            if(isset($input['email']) && $query->email === $input['email']) {
                                return $this->sendError('Email address already exists.');
                            } elseif(isset($input['mobile_no']) && $query->mobile_no === $input['mobile_no']) {
                                return $this->sendError('Mobile number already exists.');
                            }
                        }
                    }                    
                    $messgae = 'User updated successfully.';   
                    User::where('id', $input['user_id'])->update($userArr);                    
                   
                }                               
            }else{
                $userArr['password'] = $input['password'];
                $lastUserId = User::create($userArr);
                $lastUserId = $lastUserId->id;
                $messgae = 'User Register successfully.';
                if(isset($input['user_id']))
                {
                    $existingUser = User::where('id', '!=', $input['user_id'])->orWhere('email', isset($input['email']))->orWhere('mobile_no', isset($input['mobile_no']))->first();        
                    if ($existingUser) {
                        return response()->json(['success'=> false,'error' => 'User already exists with this email.'], 422);
                    }
                }
                else
                {
                    $existingUser = User::orWhere('email', isset($input['email']))->orWhere('mobile_no', isset($input['mobile_no']))->first();
                    if ($existingUser) {
                        return response()->json(['success'=> false,'error' => 'User already exists with this email.'], 422);
                    }
                }
               
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
                'contact_info' => function ($attribute, $value, $fail) use ($input) {
                    $emailProvided = isset($input['email']) && $input['email'] !== '';
                    $mobileNoProvided = isset($input['mobile_no']) && $input['mobile_no'] !== '';
            
                    if ($emailProvided && $mobileNoProvided) {
                        $fail('Provide either email or mobile number, not both.');
                    } elseif (!$emailProvided && !$mobileNoProvided) {
                        $fail('Please provide either email or mobile number.');
                    }
                },
                'password' => 'required',
            ]);
        
            if ($validator->fails()) {
                return $this->sendError($validator->errors()->first());
            }
            $oneQuery = User::with('getAllProfileimg');

            if(isset($input['email']) && $input['email']) {
                $oneQuery->where('email', $input['email']);
            }
            if(isset($input['mobile_no']) && $input['mobile_no']) {
                $oneQuery->where('mobile_no', $input['mobile_no']);
            }
            $getUser = $oneQuery->where('user_role', 'user')->first();
            if($getUser)
            {
                if($getUser->is_verified == 1)
                {
                if($getUser->user_status == 'active'){
                    if(Hash::check($input['password'], $getUser->password))
                    {
                        $updateArry = [];
                        $updateArry['online'] = 1;
                        $updateArry['last_online'] = now();                     
                        if(isset($input['email']) && $input['email']) {
                            User::where('email',$input['email'])->update($updateArry);
                            $getUser = User::with('getAllProfileimg')->where('user_role', 'user')->where('email', $input['email'])->first();
                        }else{
                            User::where('mobile_no',$input['mobile_no'])->update($updateArry);
                            $getUser = User::with('getAllProfileimg')->where('user_role', 'user')->where('mobile_no', $input['mobile_no'])->first();
                        }                        
                        $getUser->allow_privateImage_access_users = Privatealbumaccess::where('receiver_id' , $getUser->id)->where('status', '1')->get(['id as request_id', 'sender_id as user_id', 'updated_at as time']);
                        $getUser->is_blocked_users = BlockedUsers::where('sender_id' , $getUser->id)->where('is_blocked', 1)->get(['receiver_id as user_id', 'updated_at as time']);

                        $getUser->is_blocked_users = BlockedUsers::where(function($query) use ($getUser) {
                            $query->where('sender_id', $getUser->id)
                                  ->orWhere('receiver_id', $getUser->id);
                        })
                        ->where('is_blocked', 1)
                        ->get(['receiver_id as receiver_id', 'sender_id as sender_id', 'updated_at as time']);

                        $getUser->is_reports_users = Reports::where('sender_id', $getUser->id)->get(['receiver_id as user_id', 'updated_at as time']);
                        $getUser->user_subscriptions = UserSubscription::where('user_id', $getUser->id)->orderBy('id', 'desc')->first(['plan_type as subscription_plan', 'plan_price as subscription_amount']);
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
            }else{
                return $this->sendError('Please verify your email');
            }
        }
        else
        {
            return $this->sendError('Invalid email address or mobile! please try again.');
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
        $status = $request->input('status');
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
            $stringArr['status'] = $status;            
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
                $imagecount = $request->file('chat_images') ? count($request->file('chat_images')) : 0;

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
                $message = Messages::where('id', $lastInsertedId)->first();

                $getAllChatimg = ChatImages::where('message_id' , $lastInsertedId)->orderBy('id' ,'desc')->limit($imagecount)->get();

                $message->get_all_chat_with_image = $getAllChatimg;

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
                        $getMessage->update(['text' => $newText, 'status' => $status,'type' => $type]);

                        $getMessageNew = Messages::where('id', $id)->first();
                        $getAllChatimg = ChatImages::where('message_id' ,$id)->orderBy('id' ,'desc')->get();

                        $getMessageNew->get_all_chat_with_image = $getAllChatimg;

                        return response()->json(['success' => true , 'message' => $getMessageNew]);
                    } else {
                        return response()->json(['success' => false , 'message' => 'message not found! Please enter message id']);
                    }                
            }
            else if($type == "deleted" && $id != null)
            {
                $getMessage = Messages::where('id', $id)->first();
                if ($getMessage) {
                    $getMessage->update(['deleted_at' => 1 , 'type' => 'deleted']);
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
                $profileList->allow_privateImage_access_users = Privatealbumaccess::where('receiver_id' , $input['id'])->where('status', '1')->get(['id as request_id', 'sender_id as user_id', 'updated_at as time']);
                $profileList->is_friends = Friend_list::where('is_friend', 1)
                ->where(function($query) use ($input) {
                    $query->where('receiver_id', $input['id'])
                          ->orWhere('sender_id', $input['id']);
                })
                ->selectRaw('CASE 
                                WHEN receiver_id = ? THEN sender_id 
                                ELSE receiver_id 
                             END AS user_id, updated_at as time', [$input['id']])
                ->get();
            
                $profileList->is_blocked_users = BlockedUsers::where(function($query) use ($input) {
                    $query->where('sender_id', $input['id'])
                          ->orWhere('receiver_id', $input['id']);
                })
                ->where('is_blocked', 1)
                ->get(['receiver_id as receiver_id', 'sender_id as sender_id', 'updated_at as time']);
                
                $profileList->is_reports_users = Reports::where('sender_id', $input['id'])->get(['receiver_id as user_id', 'updated_at as time']);
                $profileList->user_subscriptions = UserSubscription::where('user_id', $input['id'])->orderBy('id', 'desc')->first(['plan_type as subscription_plan', 'plan_price as subscription_amount']);
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
                $user->allow_privateImage_access_users = Privatealbumaccess::where('receiver_id' , $user['id'])->where('status', '1')->get(['id as request_id','sender_id as user_id', 'updated_at as time']);
                
                $user->is_friends = Friend_list::where('is_friend', 1)
                ->where(function($query) use ($user) {
                    $query->where('receiver_id', $user['id'])
                          ->orWhere('sender_id', $user['id']);
                })
                ->selectRaw('CASE 
                                WHEN receiver_id = ? THEN sender_id 
                                ELSE receiver_id 
                             END AS user_id, updated_at as time', [$user['id']])
                ->get();

                $user->is_blocked_users = BlockedUsers::where(function($query) use ($user) {
                    $query->where('sender_id', $user['id'])
                          ->orWhere('receiver_id', $user['id']);
                })
                ->where('is_blocked', 1)
                ->get(['receiver_id as receiver_id', 'sender_id as sender_id', 'updated_at as time']);
                
                $user->is_reports_users = Reports::where('sender_id' , $user['id'])->get(['receiver_id as user_id', 'updated_at as time']);
                $user->user_subscriptions = UserSubscription::where('user_id', $user['id'])->orderBy('id', 'desc')->first(['plan_type as subscription_plan', 'plan_price as subscription_amount']);
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
        $lastRequest = Friend_list::updateOrCreate(
            ['sender_id' => $input['sender_id'], 'receiver_id' => $input['receiver_id']],
            ['is_friend' => $input['is_approved']]
        );

        if($input['is_approved'] == 1)
        {
            Friend_list::updateOrCreate(
                ['receiver_id' => $input['sender_id'], 'sender_id' => $input['receiver_id']],
                ['is_friend' => $input['is_approved']]
            );

            RequestNotification::updateOrCreate(
                ['sender_id' => $input['sender_id'], 'receiver_id' => $input['receiver_id']],
                ['read_flag' => $input['is_approved']]
            );

            RequestNotification::updateOrCreate(
                ['receiver_id' => $input['sender_id'], 'sender_id' => $input['receiver_id']],
                ['read_flag' => $input['is_approved']]
            );
            
            UsersNotification::create(['user_id' => $input['receiver_id'], 'sender_id' => $senderCheck->id, 'message' => $senderCheck->username.' accept you friend request.']);
        }

        if($input['is_approved'] == 0)
        {
            Friend_list::updateOrCreate(
                ['receiver_id' => $input['sender_id'], 'sender_id' => $input['receiver_id']],
                ['is_friend' => $input['is_approved']]
            );
            RequestNotification::updateOrCreate(
                ['sender_id' => $input['sender_id'], 'receiver_id' => $input['receiver_id']],
                ['read_flag' => $input['is_approved']]
            );
        }

        if($input['is_approved'] == 2)
        {
            Friend_list::updateOrCreate(
                ['receiver_id' => $input['sender_id'], 'sender_id' => $input['receiver_id']],
                ['is_friend' => $input['is_approved']]
            );
            RequestNotification::updateOrCreate(
                ['sender_id' => $input['sender_id'], 'receiver_id' => $input['receiver_id']],
                ['read_flag' => $input['is_approved']]
            );
        }

        $message = ($input['is_approved'] == '0' ? 'Request sent successfully to ' . $receiverCheck->username . '.' : ($input['is_approved'] == '1' ? 'Both are friends ' . $senderCheck->username .' and '.$receiverCheck->username. '.'  : 'Request decline to ' . $receiverCheck->username . '.'));

        // $message = 'Request send successfully to '.($receiverCheck ? $receiverCheck->username : '');
        return response()->json(['success' => true ,'message' => $message, 'data' => $lastRequest], 201);
    }



    public function friend_list_new(Request $request)
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

        $find_exits = Friend_list::where('receiver_id' , $input['sender_id'])->where('sender_id',$input['receiver_id'])->first();

        if($find_exits)
        {
            // Create or update the record in the database
                $lastRequest = Friend_list::updateOrCreate(
                    ['receiver_id' => $input['sender_id'], 'sender_id' => $input['receiver_id']],
                    ['is_friend' => $input['is_approved']]
                );

                RequestNotification::updateOrCreate(
                    ['receiver_id' => $input['sender_id'], 'sender_id' => $input['receiver_id']],
                    ['read_flag' => $input['is_approved'], 'is_friend' => $input['is_approved']]
                );
           
                if($input['is_approved'] == 1)
                {
                    UsersNotification::create(['user_id' => $input['receiver_id'], 'sender_id' => $senderCheck->id, 'message' => $senderCheck->username.' accept your friend request.', 'is_friend' => $input['is_approved']]);
                }
                
                if($input['is_approved'] == 2)
                {  
                    UsersNotification::where('user_id', $input['receiver_id'])->delete();
                }
            $message = ($input['is_approved'] == '0' ? 'Request sent successfully to ' . $senderCheck->username . '.' : ($input['is_approved'] == '1' ? 'Both are friends ' . $receiverCheck->username .' and '.$senderCheck->username. '.'  : 'Request decline to ' . $senderCheck->username . '.'));

        }else{

            if($input['is_approved'] == 1)
            {  
                UsersNotification::create(['user_id' => $input['sender_id'], 'sender_id' => $receiverCheck->id, 'message' => $receiverCheck->username.' accept your friend request.' , 'is_friend' => $input['is_approved']]);
            }

            if($input['is_approved'] == 2)
            {  
                UsersNotification::where('user_id', $input['sender_id'])->delete();
            }
            
            // Create or update the record in the database
            $lastRequest = Friend_list::updateOrCreate(
                ['sender_id' => $input['sender_id'], 'receiver_id' => $input['receiver_id']],
                ['is_friend' => $input['is_approved'], 'is_friend' => $input['is_approved']]
            );

            RequestNotification::updateOrCreate(
                ['sender_id' => $input['sender_id'], 'receiver_id' => $input['receiver_id']],
                ['read_flag' => $input['is_approved'], 'is_friend' => $input['is_approved']]
            );
            $message = ($input['is_approved'] == '0' ? 'Request sent successfully to ' . $receiverCheck->username . '.' : ($input['is_approved'] == '1' ? 'Both are friends ' . $senderCheck->username .' and '.$receiverCheck->username. '.'  : 'Request decline to ' . $receiverCheck->username . '.'));
        }
    
        
         // $message = 'Request send successfully to '.($receiverCheck ? $receiverCheck->username : '');
         return response()->json(['success' => true ,'message' => $message, 'data' => $lastRequest], 201);
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

        $input = $request->all();

        if(isset($input['user_id']))
        {
        $validator = Validator::make($input, [
            'user_id' => 'required'
        ]);
    
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }

        $push = RequestNotification::where('receiver_id', $input['user_id'])->whereIn('read_flag', [0])
        ->selectRaw('id ,sender_id , receiver_id, read_flag,created_at,updated_at,
            CASE 
                WHEN read_flag = 0 THEN "false" 
                ELSE null 
            END as is_accepted')
        ->get()->toArray();

        $data_notification = UsersNotification::where('user_id', $input['user_id'])->get()->toArray();
        
        $response = array_merge($data_notification, $push);
        
    }else{
        $push = RequestNotification::whereIn('read_flag', [0])
        ->selectRaw('id ,sender_id , receiver_id, read_flag,created_at,updated_at,
            CASE 
                WHEN read_flag = 0 THEN "false" 
                ELSE null 
            END as is_accepted')
        ->get()->toArray();

        $data_notification = UsersNotification::get()->toArray();
        
        $response = array_merge($data_notification, $push);
    }
    
        return $this->sendResponse($response, 'Friend request pending records');
    }

    
    public function push_notifications_message(Request $request)
    {

        try {
            $input = $request->all();
            $validator = Validator::make($input, [
                'sender_id' => 'integer|required',
                'receiver_id' => 'integer|required',
            ]);
            
            if ($validator->fails()) {
                return $this->sendError($validator->errors()->first());
            }

            $message = Messages::where('sender_id', $input['sender_id'])
                    ->where('receiver_id', $input['receiver_id'])
                    ->where('status', 'sent')
                    ->get();
                    
            return $this->sendResponse($message, 'Unread messages');

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function contactUs(Request $request)
    {
        try {
            $input = $request->all();

            $validator = Validator::make($input, [
                'user_id' => 'required',
                'subject' => 'required|regex:/^[a-zA-Z0-9\s]+$/',
                'message' => 'required|regex:/^[a-zA-Z0-9\s]+$/'
            ]);
        
            if ($validator->fails()) {
                return $this->sendError($validator->errors()->first());
            }
         
                    $user_id = $input['user_id'];
                    $message = $input['message'];
                    $subject = $input['subject'];
                    $user_data = User::where('id', $user_id)->first();
                    if(!$user_data)
                    {
                        return $this->sendError('User not found.');
                    }
                    // Check if a record with the email exists
                    // $existingRecord = ContactUs::where('user_id', $user_id)->first();
                    // if (!$existingRecord) {
                        // Create a new record if it doesn't exist                        
                        $newRecord = new ContactUs();
                        $newRecord->user_id = $user_data->id;
                        $newRecord->email = $user_data->email;
                        $newRecord->subject = $subject;
                        $newRecord->message = $message;
                        $newRecord->save();
                    // } 
                
                // $ContactUs = ContactUs::where('email', $input['email'])->first();
                // $respoArr['username'] = $user_id->username;
                // $respoArr['email'] = $ContactUs->email;
                // $respoArr['message'] = $ContactUs->message;
                // $respoArr['logo_link'] = url('/').'/'.'public/assets/img/site-logo.png';

                // Mail::send('mail/contactus', ['user' => $respoArr], function ($m) use ($respoArr, $input) {
                //     $m->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
                //     $m->to('gautam@tec-sense.com')->subject('Contact Us');
                // });

                return $this->sendResponse($user_data->email, 'Your message has been forwarded to our team. Thank you.');
            

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function IdentityVerification(Request $request)
    {
        try {
            $input = $request->all();

            $validator = Validator::make($input, [
                'user_id' => 'required',
                'government_id_name' => 'required',
                'file' => 'required'
            ]);
        
            if ($validator->fails()) {
                return $this->sendError($validator->errors()->first());
            }
         
                    $user_id = $input['user_id'];
                    $government_id_name = $input['government_id_name'];
                    
                    $user_data = User::where('id', $user_id)->first();
                    if(!$user_data)
                    {
                        return $this->sendError('User not found.');
                    }

                    if(isset($user_data) && $user_data->is_identityverification == 'approved')
                    {
                        return $this->sendResponse([],'Your Identity Verification is already verified');
                    }

                    if($file = $request->file('file'))
                    {
                        $path = 'public/uploads/user/IdentityVerification/';
            
                        $filename = time() . '_' . $file->getClientOriginalName();
                        $file->move($path, $filename);
                        $img = 'public/uploads/user/IdentityVerification/' . $filename;                

                        if(isset($input['user_id']) && $input['user_id']!= "")
                        {
                            $getUserDetails = User::where('id', $input['user_id'])->first();
                            if ($getUserDetails) {
                                $proFilePath = $getUserDetails->identity_file;
                                $proPath = substr(strstr($proFilePath, 'public/'), strlen('public/'));
            
                                if (file_exists(public_path($proPath))) {
                                    \File::delete(public_path($proPath));
                                }
                            }
                        }
                    }

                    $existingRecord = User::where('id', $user_id)->first();
                    if (!$existingRecord) {
                        // Create a new record if it doesn't exist                        
                        $newRecord = new User();
                        $newRecord->identity_file = $img;
                        $newRecord->is_identityverification = 'pending';
                        $newRecord->government_id_name = $government_id_name;
                        $newRecord->save();
                    } else {
                        // Update the existing record
                        $existingRecord->identity_file = $img;
                        $existingRecord->government_id_name = $government_id_name;
                        $existingRecord->is_identityverification = 'pending';
                        $existingRecord->save();
                    } 
                
                return $this->sendResponse($user_data->email, 'Your Identity Verification is submitted success');
            

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function readMessage(Request $request)
    {
        try {
            $input = $request->all();
            $validator = Validator::make($input, [
                'sender_id' => 'integer|required',
                'receiver_id' => 'integer|required',
                'messageId'=> 'string|required', // Change validation to string
                'status' => 'string|required',
            ]);
        
            if ($validator->fails()) {
                return $this->sendError($validator->errors()->first());
            }
        
            $messageIds = explode(',', $input['messageId']); // Split comma-separated IDs into an array
        
            // Update messages with specified IDs
            Messages::where('sender_id', $input['sender_id'])
                    ->where('receiver_id', $input['receiver_id'])
                    ->whereIn('id', $messageIds) // Only update messages with specified IDs
                    ->update(['status' => $input['status']]); // Update status
        
            // Check if any messages were updated
            $updatedMessagesCount = Messages::where('sender_id', $input['sender_id'])
                                            ->where('receiver_id', $input['receiver_id'])
                                            ->whereIn('id', $messageIds)
                                            ->count();
        
            if ($updatedMessagesCount > 0) {
                return $this->sendResponse([], 'Messages read successfully');
            } else {
                return response()->json(['success' => false ,'message' => 'No messages found with the provided IDs'], 404);
            }
        
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function readPrivateAlbumAccess(Request $request)
    {
        try {
            $input = $request->all();
            $validator = Validator::make($input, [
                'user_id' => 'integer|required',
            ]);
        
            if ($validator->fails()) {
                return $this->sendError($validator->errors()->first());
            }

         // Update messages with specified IDs
         Privatealbumaccess::where('receiver_id', $input['user_id'])->update(['flag' => 1]); // Update status

         return $this->sendResponse([], 'Private album access read success');
        
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function readFriendRequestNotifiaction(Request $request)
    {
        try {
            $input = $request->all();
            $validator = Validator::make($input, [
                'id' => 'integer|required',
            ]);
        
            if ($validator->fails()) {
                return $this->sendError($validator->errors()->first());
            }

         // Update messages with specified IDs
         $data = UsersNotification::where('id', $input['id'])->update(['read_unread' => 1]); // Update status
         
         if($data == 1)
         {
            return $this->sendResponse([], 'Congratulations You both are friends.');
         }
         else{
            return $this->sendResponse([], 'Data not found');
         }

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
    public function EliteSupport(Request $request)
    {
        try {
            $input = $request->all();
            
            // Validate the input
            $validator = Validator::make($input, [
                'user_id' => 'integer|integer',
                'type_id' => 'required|integer|between:1,7',
            ]);
                // If validation fails, return error response
            if ($validator->fails()) {
                return $this->sendError($validator->errors()->first());
            }

            // Check if the entry already exists
            $existingEntry = UserAdminCommunication::where('user_id', $input['user_id'])
                                                    ->where('support_id', $input['type_id'])
                                                    ->first();

            if ($existingEntry) {
                // Handle the case where the entry already exists
                // For example, you might want to return an error response
                return $this->sendError('Entry already exists for the given user and type');
            }

            // Create the new entry
            $blockedUser = UserAdminCommunication::create([
                'user_id' => $input['user_id'],
                'support_id' => $input['type_id'],
            ]);
    
            // Return success response with messages
            return $this->sendResponse($blockedUser, 'Messages generated successfully.');
    
        } catch (\Exception $e) {
            // Return error response in case of exception
            return $this->sendError($e->getMessage());
        }
    }

    public function EliteSupportData(Request $request)
    {
        try {
            $input = $request->all();
            
            // Validate the input
            $validator = Validator::make($input, [
                'user_id' => 'integer|integer',
            ]);
                // If validation fails, return error response
            if ($validator->fails()) {
                return $this->sendError($validator->errors()->first());
            }
            if(isset($input['user_id']) && $input['user_id'])
            {
                   // Check if the entry already exists
                    $existingEntry = User::where('id', $input['user_id'])->first();
                        if (!$existingEntry) {
                        return $this->sendError('User not exists');
                        }
                $entry = UserAdminCommunication::with('getSupport')->where('user_id', $input['user_id'])
                ->get();
            }else{
                $entry = UserAdminCommunication::with('getSupport')->get();
            }
            // Return success response with messages
            return $this->sendResponse($entry, 'your answers be like');
    
        } catch (\Exception $e) {
            // Return error response in case of exception
            return $this->sendError($e->getMessage());
        }
    }

    public function LaguageMaster(Request $request)
    {
        try {
            $input = $request->all();
            
            // Validate the input
            $validator = Validator::make($input, [
                'english_string' => 'required|string',
            ]);
                // If validation fails, return error response
            if ($validator->fails()) {
                return $this->sendError($validator->errors()->first());
            }
                        
            // Check if the English string exists in the database   
            $existingData = LanguageMaster::where('english_string', $input['english_string'])->first();
            
            if ($existingData) {
                // If exists, return the existing data
                return $this->sendResponse($existingData, 'Data found.');
            } else {
                // If not exists, create a new entry
                $varString = 'string_' . str_replace(' ', '_', $input['english_string']);
                $newData = LanguageMaster::create(['english_string' => $input['english_string'], 'var_string' => $varString]);
                return $this->sendResponse($newData, 'New entry created successfully.');
            }
    
        } catch (\Exception $e) {
            // Return error response in case of exception
            return $this->sendError($e->getMessage());
        }
    }

    public function GetLaguageMaster(Request $request)
    {
        try {
            $input = $request->all();
            
            // Validate the input
                
            // Check if the English string exists in the database   
            $existingData = LanguageMaster::get();
            
         
            if ($existingData) {
                // Transform the existing data into the desired format
                $transformedData = [];

                foreach ($existingData as $data) {
                    // Convert var_string to lowercase and replace underscores with spaces
                    $key = strtolower(str_replace('string_', '', $data->var_string));

                    // Add the transformed data to the array
                    $transformedData['string_'.$key] = [
                        'english_string' => $data->english_string,
                        'danish_string' => $data->danish_string ?? $data->english_string // Use english_string if danish_string is null
                    ];
                }

                return $this->sendResponse($transformedData, 'Data found.');
            } else {
                return $this->sendResponse([], 'Data not found');
            }
        } catch (\Exception $e) {
            // Return error response in case of exception
            return $this->sendError($e->getMessage());
        }
    }    

    public function GetBroadcast(Request $request)
    {
        try {
            $input = $request->all();            
            // Check if the English string exists in the database   
            $existingData = UserElitesupport::where('type', 'broadcast')->get();            
         
            if ($existingData) {
                // Transform the existing data into the desired format
            
                return $this->sendResponse($existingData, 'Broadcast data found.');
            } else {
                return $this->sendResponse([], 'Data not found');
            }
        } catch (\Exception $e) {
            // Return error response in case of exception
            return $this->sendError($e->getMessage());
        }
    }    
}