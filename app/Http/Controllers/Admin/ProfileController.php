<?php
 
namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\User_images;
use App\Models\User_Report;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\Controller as Controller;

use Auth;
use Validator;

class ProfileController extends Controller
{
    /**
     * Show the profile for a given user.
     */
    public function index()
    {
        if (Auth::check()) 
        {
            return view('admin.profile.add-profile');
        }
        else
        {
            return view('admin.login');
        }
    }

    public function profileregister(Request $request)
    {
        $input = $request->all();
        // Check if the email already exists in the database
        $existingUser = User::where('email', $input['email'])->first();
        if ($existingUser) {
            // return response()->json(['error' => 'User already exists with this email.'], 422);
            return redirect()->back()->with('error', 'User already exists with this email.');
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

        $input['username'] = isset($_POST['username']) ? $_POST['username'] : '';
        $input['user_role'] = 'user';
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

                $attachment['user_id'] = $lastUserId->id;
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

                $attachment['user_id'] = $lastUserId->id;
                $attachment['public_images'] = $img;
                $attachment['image_type'] = 'private';
                User_images::create($attachment);
            }
        }   
        
        return redirect('profiles')->with('success', 'Profile added successfully.');
    }

    public function profileindex()
    {
        if (Auth::check()) 
        {
            return view('admin.profile.profile');
        }
        else
        {
            return view('admin.login');
        }
    }

    public function profilelist(Request $request)
    {
        
        $input = $request->all();
        $list_profiles = [];
        $search = $input['search'];
        $entries_per_page = $input['entries_per_page'];

        $list_profiles = User::where('user_role', 'user')
                            ->when(isset($search) && $search != '', function ($query) use ($search) {
                                $query->where(function ($subquery) use ($search) {
                                    $subquery->where('username', 'like', '%' . $search . '%')
                                            ->orWhere('email', 'like', '%' . $search . '%');
                                });
                            })
                            ->orderBy('id', 'desc')
                            ->paginate($entries_per_page);
        
        return view('admin.profile.list-profile',compact('list_profiles'));
    }

    public function profileedit($id)
    {
        if (Auth::check()) 
        {
            $decrypted_id = Crypt::decrypt($id);
            $list_profiles = User::where('id', $decrypted_id)->first();
            $getimage = User_images::where('user_id', $decrypted_id)->get();
            return view('admin.profile.edit-profile',compact('list_profiles', 'getimage'));
        }
        else
        {
            return view('admin.login');
        }
    }

    public function profileupdate(Request $request) 
    {
        $input = $request->all();
        
        $user_id = $input['user_id'];
        $input = [];


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

        $input['username'] = isset($_POST['username']) ? $_POST['username'] : '';
        $input['user_role'] = 'user';
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

        if(!empty($request->file('public_images')))
        {
            foreach ($request->file('public_images') as $file)
            {
                $path = 'public/uploads/user/public_images/';

                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move($path, $filename);

                $img = 'public/uploads/user/public_images/' . $filename;

                $attachment['user_id'] = $user_id;
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

                $attachment['user_id'] = $user_id;
                $attachment['public_images'] = $img;
                $attachment['image_type'] = 'private';
                User_images::create($attachment);
            }
        }    
        return redirect('profiles')->with('success', 'Profile update successfully.');
    }

    public function profiledelete(Request $request)
    {
        $input = $request->all();
        User::where('id', $input['profile_id'])->delete();
        return response()->json(['success' => true, 'message' => 'Profile deleted successfully.']);
    }

    function removeuserimage(Request $request)
    {
        $id = $request->input('id');
        $userimage = User_images::find($id);

        if ($userimage) {
            $proFilePath = $userimage->public_images;
            $proPath = substr(strstr($proFilePath, 'public/'), strlen('public/'));

            if (file_exists(public_path($proPath))) {
                \File::delete(public_path($proPath));
            }
        }

        $userimage->delete();
    }
    
    
    public function IdentityVerificationIndex()
    {
        if (Auth::check()) 
        {
            return view('admin.profile.IdentityVerification');
        }
        else
        {
            return view('admin.login');
        }
    }

    public function IdentityVerification(Request $request)
    {
        $input = $request->all();
        $list_profiles = [];
        $search = $input['search'];
        $entries_per_page = $input['entries_per_page'];

        $list_profiles = User::where('user_role', 'user')
        ->whereNotNull('is_identityverification') // Exclude records where is_identityverification is null
        ->when(isset($search) && $search != '', function ($query) use ($search) {
            $query->where(function ($subquery) use ($search) {
                $subquery->where('username', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%');
            });
        })
        ->orderBy('updated_at', 'desc') // Order by updated_at column
        ->paginate($entries_per_page);

        
        return view('admin.profile.IdentityVerification-list-profile',compact('list_profiles'));
    }

    public function CheckIdentity(Request $request)
    {
        $input = $request->all();
        $user_id = $input['id'];

        if($input['is_identityverification'] == 1)
        {
            $input['is_identityverification'] = 'approved';
            $user = User::where('id', $user_id)->update($input);
        }

        if($input['is_identityverification'] == 0)
        {
            $input['is_identityverification'] = 'rejected';
            $user = User::where('id', $user_id)->update($input);
        }
    
        return true;
    }

    
    
}


