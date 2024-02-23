<?php
 
 namespace App\Http\Controllers\Api\V1;

use App\Models\Newsletter;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Mail\NewsletterEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\Api\BaseController as BaseController;

use Validator;
 
class NewslettersController extends BaseController
{
    public function newsletter(Request $request){
        try {
            $input = $request->all();

            $validator = Validator::make($input, [
                'email' => 'required',
            ]);

            if ($validator->fails()) {
                return $this->sendError($validator->errors()->first());
            }

            $existingUser = Newsletter::where('email', $_POST['email'])->first();
            if ($existingUser) {
                return response()->json(['success'=> false,'error' => 'User already exists with this email.'], 422);
            }

            $input['email'] = isset($_POST['email']) ? $_POST['email'] : '';
            $input['flag_subscription'] = 1;
            $lastUserId = Newsletter::create($input);
            $encrypteduserId = Crypt::encrypt($lastUserId->id);
            $mailData = [
                "id" => $encrypteduserId,
            ];
            Mail::to($input['email'])->send(new NewsletterEmail($mailData));



            return response()->json(['success' => true, 'message' => 'Email send successfully.'], 200);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function unsubscribe(Request $request){
        try {
    
            $userId = $request->input('id');
            $id = Crypt::decrypt($userId);
            $user = Newsletter::find($id);
    
            if (!$user) {
                return response()->json(['error' => 'User not found.'], 404);
            }
    
            $user->update(['flag_subscription' => 0]);
    
            return view('mail.unsubscribe');
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
}