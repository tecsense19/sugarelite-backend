<?php
 
namespace App\Http\Controllers\Admin;

use Illuminate\View\View;
use App\Http\Controllers\Controller as Controller;
 
class HomeController extends Controller
{
    /**
     * Show the profile for a given user.
     */
    public function index(): View
    {
        return view('admin.dashboard.dashboard');
    }
}


