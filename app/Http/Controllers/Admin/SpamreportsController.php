<?php
 
namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Reports;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\Controller as Controller;

use Auth;
 
class SpamreportsController extends Controller
{
    public function index(){
        return view('admin.spam_reports.user_report');
    }

    public function userreportList(Request $request){

        $input = $request->all();
        $search = $input['search'] ?? null;
        $entries_per_page = $input['entries_per_page'];

        $query = Reports::with(['sender', 'receiver'])
            ->where(function ($query) use ($search) {
                if (isset($search)) {
                    $query->whereHas('sender', function($q) use ($search) {
                        $q->where('username', 'like', '%' . $search . '%');
                    })->orWhereHas('receiver', function($q) use ($search) {
                        $q->where('username', 'like', '%' . $search . '%');
                    });
                }
            })
            ->orderBy('id', 'desc');

        // Paginate the results after applying the search condition
        $userreportList = $query->paginate($entries_per_page);

        return view('admin.spam_reports.list_user_report', compact('userreportList'));

    }
}