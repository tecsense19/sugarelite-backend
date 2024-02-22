<?php
 
namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\User_images;
use App\Models\User_Report;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\Controller as Controller;
 
class ProfileController extends Controller
{
    /**
     * Show the profile for a given user.
     */
    public function index()
    {
        return view('admin.profile.add-profile');
    }

    public function profileregister(Request $request)
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
        
        sleep(1);
        $imgUrl = env('APP_URL') ? env('APP_URL') . ('/'.$img) : url('/') . ('/'.$img);
        $input['avatar_url'] = $imgUrl;

        $input['username'] = isset($_POST['username']) ? $_POST['username'] : '';
        $input['user_role'] = isset($_POST['user_role']) ? $_POST['user_role'] : '';
        $input['sex'] = isset($_POST['sex']) ? $_POST['sex'] : '';
        $input['height'] = isset($_POST['height']) ? $_POST['height'] : '';
        $input['premium'] = isset($_POST['premium']) ? $_POST['premium'] : '';
        $input['age'] = isset($_POST['age']) ? $_POST['age'] : '';
        $input['weight'] = isset($_POST['weight']) ? $_POST['weight'] : '';
        $input['country'] = isset($_POST['country']) ? $_POST['country'] : '';
        $input['sugar_type'] = isset($_POST['sugar_type']) ? $_POST['sugar_type'] : '';
        $input['birthdate'] = isset($_POST['birthdate']) ? $_POST['birthdate'] : '';
        $input['email'] = isset($_POST['email']) ? $_POST['email'] : '';
        $input['password'] = isset($_POST['password']) ? $_POST['password'] : '';
        $input['region'] = isset($_POST['region']) ? $_POST['region'] : '';
        $input['bio'] = isset($_POST['bio']) ? $_POST['bio'] : '';
        $input['ethnicity'] = isset($_POST['ethnicity']) ? $_POST['ethnicity'] : '';
        $input['body_structure'] = isset($_POST['body_structure']) ? $_POST['body_structure'] : '';
        $input['hair_color'] = isset($_POST['hair_color']) ? $_POST['hair_color'] : '';
        $input['piercings'] = isset($_POST['piercings']) ? $_POST['piercings'] : '';
        $input['tattoos'] = isset($_POST['tattoos']) ? $_POST['tattoos'] : '';
        $input['education'] = isset($_POST['education']) ? $_POST['education'] : '';
        $input['smoking'] = isset($_POST['smoking']) ? $_POST['smoking'] : '';
        $input['drinks'] = isset($_POST['drinks']) ? $_POST['drinks'] : '';
        $input['employment'] = isset($_POST['employment']) ? $_POST['employment'] : '';
        $input['civil_status'] = isset($_POST['civil_status']) ? $_POST['civil_status'] : '';
        $input['user_status'] = isset($_POST['user_status']) ? $_POST['user_status'] : '';
        // Create the user
        $lastUserId = User::create($input);

        // 
        if (!empty($request->hasFile('public_images'))) {
            $path = 'storage/app/public';
        
            foreach ($request->file('public_images') as $file) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move($path, $filename);
        
                $img = 'storage/app/public/' . $filename;
                $imgUrl = env('APP_URL') ? env('APP_URL') . ('/'.$img) : url('/') . ('/'.$img);
                // Store the array of new file paths in the database or perform other actions
                $attachment['user_id'] = $lastUserId->id;
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
                $attachment['user_id'] = $lastUserId->id;
                $attachment['public_images'] = $imgUrl;
                $attachment['image_type'] = 'private';
                User_images::create($attachment);
            }
        }
    
        
        return redirect('profiles');
    }

    public function profileindex(){
        return view('admin.profile.profile');
    }

    public function profilelist(Request $request)
    {
        
        $input = $request->all();
        $list_profiles = [];
        $search = $input['search'];
        $entries_per_page = $input['entries_per_page'];
        if(isset($search) && $search != '')
        {
            $list_profiles = User::with(['getUsersreport'])->where('user_role', 'user')->where('username', 'like', '%' . $search . '%')->orWhere('email', 'like', '%' . $search . '%')->orderBy('id', 'desc')->paginate($entries_per_page);
        }
        else
        {
            $list_profiles = User::with(['getUsersreport'])->where('user_role', 'user')->paginate($entries_per_page);
        }
        // echo '<pre>';print_r($list_profiles);echo '</pre>';
        return view('admin.profile.list-profile',compact('list_profiles'));
    }

    public function profileedit($id)
    {
        $decrypted_id = Crypt::decrypt($id);
        $list_profiles = User::where('id', $decrypted_id)->first();
        $getimage = User_images::where('user_id', $decrypted_id)->get();
        return view('admin.profile.edit-profile',compact('list_profiles', 'getimage'));
    }

    public function profileupdate(Request $request) {
        $input = $request->all();
        
        $user_id = $input['user_id'];
        $input = [];
        if (!empty($files = $request->file('avatar_url'))) {
                $path = 'storage/app/public';
                $filename = time() . '_' . $files->getClientOriginalName();
                $files->move($path, $filename);
                $img = 'storage/app/public/' . $filename;
                $imgUrl = env('APP_URL') ? env('APP_URL') . ('/'.$img) : url('/') . ('/'.$img);
                $input['avatar_url'] = $imgUrl;
        }
        
        

        $input['username'] = isset($_POST['username']) ? $_POST['username'] : '';
        $input['user_role'] = isset($_POST['user_role']) ? $_POST['user_role'] : '';
        $input['sex'] = isset($_POST['sex']) ? $_POST['sex'] : '';
        $input['height'] = isset($_POST['height']) ? $_POST['height'] : '';
        $input['premium'] = isset($_POST['premium']) ? $_POST['premium'] : '';
        $input['age'] = isset($_POST['age']) ? $_POST['age'] : '';
        $input['weight'] = isset($_POST['weight']) ? $_POST['weight'] : '';
        $input['country'] = isset($_POST['country']) ? $_POST['country'] : '';
        $input['sugar_type'] = isset($_POST['sugar_type']) ? $_POST['sugar_type'] : '';
        $input['birthdate'] = isset($_POST['birthdate']) ? $_POST['birthdate'] : '';
        $input['email'] = isset($_POST['email']) ? $_POST['email'] : '';
        $input['region'] = isset($_POST['region']) ? $_POST['region'] : '';
        $input['bio'] = isset($_POST['bio']) ? $_POST['bio'] : '';
        $input['ethnicity'] = isset($_POST['ethnicity']) ? $_POST['ethnicity'] : '';
        $input['body_structure'] = isset($_POST['body_structure']) ? $_POST['body_structure'] : '';
        $input['hair_color'] = isset($_POST['hair_color']) ? $_POST['hair_color'] : '';
        $input['piercings'] = isset($_POST['piercings']) ? $_POST['piercings'] : '';
        $input['tattoos'] = isset($_POST['tattoos']) ? $_POST['tattoos'] : '';
        $input['education'] = isset($_POST['education']) ? $_POST['education'] : '';
        $input['smoking'] = isset($_POST['smoking']) ? $_POST['smoking'] : '';
        $input['drinks'] = isset($_POST['drinks']) ? $_POST['drinks'] : '';
        $input['employment'] = isset($_POST['employment']) ? $_POST['employment'] : '';
        $input['civil_status'] = isset($_POST['civil_status']) ? $_POST['civil_status'] : '';
        $input['user_status'] = isset($_POST['user_status']) ? $_POST['user_status'] : '';
        
        // update the user
        $user = User::where('id', $user_id)->update($input);

        if (!empty($request->hasFile('public_images'))) {
            $path = 'storage/app/public';
        
            foreach ($request->file('public_images') as $file) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move($path, $filename);
        
                $img = 'storage/app/public/' . $filename;
                $imgUrl = env('APP_URL') ? env('APP_URL') . ('/'.$img) : url('/') . ('/'.$img);
                // Store the array of new file paths in the database or perform other actions
                $attachment['user_id'] = $user_id;
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
                $attachment['user_id'] = $user_id;
                $attachment['public_images'] = $imgUrl;
                $attachment['image_type'] = 'private';
                User_images::create($attachment);
            }
        }
    
        return redirect('profiles');
    }

    public function profiledelete(Request $request){
        $input = $request->all();
        User::where('id', $input['profile_id'])->delete();
        return response()->json(['success' => true, 'message' => 'Booking deleted successfully.']);
    }

    function removeuserimage(Request $request){
        $id = $request->input('id');
        $userimage = User_images::find($id);
        $userimage->delete();
    }
    
}


