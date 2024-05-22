<?php
 
namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\LanguageMaster;
use App\Models\Plans;
use Illuminate\View\View;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;

use Auth;
use Hash;

class LanguageMasterController extends Controller
{
    /**
     * Show the profile for a given user.
     */
    public function index()
    {
        if (Auth::check()) 
        {
            $getLanguage = LanguageMaster::orderBy('id', 'desc')->paginate(10);
            return view('admin.language.addEdit', compact('getLanguage'));
        }
        else
        {
            return view('admin.login');
        }
    }

   

    public function saveLanguage(Request $request)
    {
        try {
            $input = $request->all();
            
            // Validate the incoming request data
            $request->validate([                
                'english_string.*' => 'required',
                'danish_string.*' => 'required',
            ]);
            
            $english_string = $request->english_string ? array_reverse($request->english_string) : [];
            $danish_string = $request->danish_string ? array_reverse($request->danish_string) : [];
            $var_string = $request->var_string ? array_reverse($request->var_string) : [];

            for ($i=0; $i < count($english_string); $i++) 
            { 
                if(isset($var_string[$i]) && !empty($var_string[$i]))
                {
                    $existingPair = LanguageMaster::where('var_string', $var_string[$i])->first();
                    if($existingPair)
                    {
                        LanguageMaster::updateOrCreate(
                            ['var_string' => $var_string[$i]], // Check if record with this ID exists
                            [
                                'var_string' => $var_string[$i],
                                'english_string' => $english_string[$i],
                                'danish_string' => $danish_string[$i],
                            ]
                        );
                    }
                    else
                    {
                        $varStringa = 'string_' . str_replace(' ', '_', $english_string[$i]);
                        LanguageMaster::updateOrCreate(
                            ['var_string' => $varStringa], // Check if record with this ID exists
                            [
                                'var_string' => $varStringa,
                                'english_string' => $english_string[$i],
                                'danish_string' => $danish_string[$i],
                            ]
                        );
                    }
                }
                else
                {
                    $varStringa = 'string_' . str_replace(' ', '_', $english_string[$i]);
                    LanguageMaster::updateOrCreate(
                        ['var_string' => $varStringa], // Check if record with this ID exists
                        [
                            'var_string' => $varStringa,
                            'english_string' => $english_string[$i],
                            'danish_string' => $danish_string[$i],
                        ]
                    );
                }
            }

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
        $language = LanguageMaster::find($id);
 
        // If the language entry exists, delete it
        if ($language) {
            $language->delete();
            return redirect()->back()->with('success', 'Language entry deleted successfully.');
        } else {
            return redirect()->back()->with('error', 'Language entry not found.');
        }
    }
}

