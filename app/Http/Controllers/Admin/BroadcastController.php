<?php
 
namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\LanguageMaster;
use App\Models\Plans;
use App\Models\UserElitesupport;
use Illuminate\View\View;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;

use Auth;
use Hash;

class BroadcastController extends Controller
{
    /**
     * Show the profile for a given user.
     */
    public function index()
    {
        if (Auth::check()) 
        {
            $userElitesupport = UserElitesupport::where('type', 'broadcast')->paginate(10);
            return view('admin.broadcast.addEdit', compact('userElitesupport'));
        }
        else
        {
            return view('admin.login');
        }
    }

   

public function saveBroadcast(Request $request)
{
    try {
        $input = $request->all();
        // Create a new LanguageMaster model instance
        $broadcast = new UserElitesupport();
        
        $broadcast->title = 'Broadcast'; 
        $broadcast->type = isset($input) ? $input['broadcast'] : '';
        $broadcast->description = isset($input) ? $input['broadcast_message'] : '';        
        
        // Save the model instance
        $broadcast->save();
     
        $message = 'Data saved successfully';
        // Optionally, you can redirect the user after saving
        return redirect()->back()->withSuccess($message);
    
    } catch (\Exception $e) {
        return redirect()->back()->withError($e->getMessage());
    }
}

    public function delete(Request $request, $id)
    {
        // Find the language entry by ID
        $language = UserElitesupport::find($id);
 
        // If the language entry exists, delete it
        if ($language) {
            $language->delete();
            return redirect()->back()->with('success', 'broadcast entry deleted successfully.');
        } else {
            return redirect()->back()->with('error', 'broadcast entry not found.');
        }
    }
}

