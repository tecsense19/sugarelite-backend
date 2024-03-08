<?php
 
namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\View\View;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;

use Hash;

class HomeController extends Controller
{
    /**
     * Show the profile for a given user.
     */
    public function index(): View
    {
        $totalUsers = User::where('user_role', 'user')->count();
        $activeUsers = User::where('user_role', 'user')->where('user_status', 'active')->count();
        return view('admin.dashboard.dashboard',compact('totalUsers','activeUsers'));
    }

    public function profile()
    {
        if (Auth::check()) 
        {
            return view('admin.adminprofile.profile');
        }
        else
        {
            return view('admin.login');
        }
    }

    public function profileUpdate(Request $request)
    {
        try {
            $input = $request->all();

            $userData = [];
            $userData['username'] = isset($input['full_name']) ? $input['full_name'] : '';
            $userData['country'] = isset($input['country']) ? $input['country'] : '';
            $userData['email'] = isset($input['email']) ? $input['email'] : '';

            if($file = $request->file('avatar_url'))
            {
                $path = 'public/uploads/user/';

                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move($path, $filename);

                $img = 'public/uploads/user/' . $filename;
                
                $userData['avatar_url'] = $img;

                if($input['user_id'])
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

            User::where('id', $input['user_id'])->update($userData);

            return redirect()->back()->withSuccess('Profile updated successfully.');

        } catch (\Exception $e) {
            return redirect()->back()->withError($e->getMessage());
        }
    }

    public function updatePassword(Request $request)
    {
        try {
            $input = $request->all();

            $getUserDetails = User::where('id', $input['user_id'])->first();

            if($getUserDetails)
            {
                if(Hash::check($input['old_password'], $getUserDetails->password))
                {
                    $userData = [];
                    $userData['password'] = isset($input['new_password']) ? Hash::make($input['new_password']) : '';

                    User::where('id', $input['user_id'])->update($userData);

                    return redirect()->back()->withSuccess('Password updated successfully.');
                }
                else
                {
                    return redirect()->back()->withError('Current password are not match.');
                }
            }
            else
            {
                return redirect()->back()->withError('Invalid user.');
            }

        } catch (\Exception $e) {
            return redirect()->back()->withError($e->getMessage());
        }
    }

    public function view_SubmitforgotPassword($id,)
    {
        try {
            $decrypted_id = Crypt::decryptString($id);
            return view('mail.view_forgot_pass',compact('decrypted_id'));
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function Submit_forgotPassword(Request $request)
    {
        try {
            $input = $request->all();
            $getUserDetails = User::where('id', $input['id'])->first();
            if($getUserDetails->forgot_pass == 0)
            {
                if($input['newpass'] == $input['confirmpass'])
                {
                    $dataArr = [];
                    $dataArr['forgot_pass_date'] = date('Y-m-d H:i:s');
                    $dataArr['password'] = Hash::make($input['newpass']);
                    $dataArr['forgot_pass'] = 1;
                    User::where('id', $input['id'])->update($dataArr);
                    return redirect('/')->withSuccess('Password updated successfully.');
                }
                else
                {
                    return redirect()->back()->withError('Confirm password are not match.');
                }
            }
            else
            {
                return redirect()->back()->withError('Your link has been expired');
            }
        } 
        catch (\Exception $e) 
        {
            return $this->sendError($e->getMessage());
        }
    }
}


