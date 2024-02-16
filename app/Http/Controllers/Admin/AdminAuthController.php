<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Hash;
use Session;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller as Controller;

class AdminAuthController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }
        
        return view('admin.login');
    }  
      
    public function Login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
   
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            return redirect()->route('admin.dashboard');
        }
  
        return redirect("/")->withError('Login details are not valid');
    }
    
    public function signOut() {
        Session::flush();
        Auth::logout();
  
        return redirect()->route('admin.login');
    }
}

