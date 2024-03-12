<?php
 
namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\User_Report;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\Controller as Controller;

use Auth;
 
class UserreportController extends Controller
{
    public function index(){
        return view('admin.user_report.user_report');
    }

    public function userreportList(Request $request){

        $input = $request->all();

        $userreportList = [];

        $search = $input['search'];
        $fromDate = $input['from_date'];
        $toDate = $input['to_date'];
        $user_status = $input['user_status'];
        $entries_per_page = $input['entries_per_page'];
        
        $query = User::with('getLastSubscription')->where('user_role', 'user')->orderBy('id', 'desc');

        $query->where(function ($query) use ($search, $fromDate, $toDate, $user_status) {
            if (isset($search)) {                  
                $query->where('username', 'like', '%' . $search . '%')
                    ->orWhere('user_status', 'like', $search);
            }

            if (isset($fromDate) && isset($toDate)) {
                $query->whereDate('created_at', '>=', date('Y-m-d', strtotime($fromDate)))
                    ->whereDate('created_at', '<=', date('Y-m-d', strtotime($toDate)));
            }

            if (isset($user_status)) {
                $query->where('user_status', 'like', $user_status);
            }
        });

        $userreportList = $query->paginate($entries_per_page);

        return view('admin.user_report.list_user_report', compact('userreportList'));
    }
}