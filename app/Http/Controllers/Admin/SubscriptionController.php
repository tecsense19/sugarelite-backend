<?php
 
namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Plans;
use Illuminate\View\View;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;

use Auth;
use Hash;

class SubscriptionController extends Controller
{
    /**
     * Show the profile for a given user.
     */
    public function subscriptions()
    {
        if (Auth::check()) 
        {
            $getPlans = Plans::first();
            return view('admin.subscriptions.subscriptions', compact('getPlans'));
        }
        else
        {
            return view('admin.login');
        }
    }

    public function getSubscriptionsList(Request $request)
    {
        
        $input = $request->all();
        $list_profiles = [];
        $search = $input['search'];
        $entries_per_page = $input['entries_per_page'];

        $subscriptionList = User::where('user_role', 'user')
                            ->when(isset($search) && $search != '', function ($query) use ($search) {
                                $query->where(function ($subquery) use ($search) {
                                    $subquery->where('username', 'like', '%' . $search . '%')
                                            ->orWhere('email', 'like', '%' . $search . '%');
                                });
                            })
                            ->orderBy('id', 'desc')
                            ->paginate($entries_per_page);
        
        return view('admin.subscriptions.list',compact('subscriptionList'));
    }
}