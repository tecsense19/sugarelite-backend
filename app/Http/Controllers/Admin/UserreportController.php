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
        
    if (isset($search) || isset($fromDate) && isset($toDate) || isset($user_status)) {
        $query = User_Report::with(['getusers'])
        ->orderBy('id', 'desc');
        $query->where(function ($query) use ($search, $fromDate, $toDate, $user_status) {
            if (isset($search)) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->whereHas('getusers', function ($subSubQuery) use ($search) {
                        $subSubQuery->where('username', 'like', '%' . $search . '%')
                            ->orWhere('user_status', 'like', $search);
                    });
                })
                ->orWhere('payment_recurring_date', 'like', '%' . $search . '%')
                ->orWhere('payment_verification', 'like', '%' . $search . '%')
                ->orWhere('subscription', 'like', '%' . $search . '%');
            }
            
            if (isset($fromDate) && isset($toDate)) {
                $query->WhereBetween('register_date', [$fromDate, $toDate]);
            }

            if (isset($user_status)) {
                $query->WhereHas('getusers', function ($subQuery) use ($user_status) {
                    $subQuery->where('user_status', 'like', $user_status);
                });
            }
        });
        $userreportList = $query->paginate($entries_per_page);
        // echo '<pre>';print_r($userreportList);echo '</pre>';die;
    }

        else
        {
            $userreportList = User_Report::with(['getusers'])->orderBy('id', 'desc')->paginate($entries_per_page);
        }

        return view('admin.user_report.list_user_report', compact('userreportList'));
    }
}