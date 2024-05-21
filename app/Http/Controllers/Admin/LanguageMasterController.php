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
            $getLanguage = LanguageMaster::paginate(10);
            return view('admin.language.addEdit', compact('getLanguage'));
        }
        else
        {
            return view('admin.login');
        }
    }

    // public function saveLanguage(Request $request)
    // {
    //     try {
    //         $input = $request->all();
        
    //         // Validate the incoming request data
    //         $request->validate([                
    //             'english_string.*' => 'required',
    //             'danish_string.*' => 'required',
    //         ]);
            
    //         // Array to store error messages for duplicate entries
    //         $errors = [];      
        
    //         // Loop through the var_string array and save each pair
    //         foreach ($request->english_string as $key => $english_string) {
                
    //             $danishString = $request->danish_string[$key];
                
    //             // Check if the var_string already exists
    //             $existingPair = LanguageMaster::where('english_string', $english_string)->whereNotNull('danish_string')->exists();

               
                
    //             if ($existingPair) {

    //                 $existingPair->update(['danish_string' => $danishString]);

    //                 $errors[] = "The pair with english_string '$english_string' already exists.";
    //             } else {
    //                 // Create a new LanguageMaster model instance
    //                 $language = new LanguageMaster();
                    
    //                 // Assign the var_string, english_string, and danish_string to the appropriate attributes in the model
    //                 $language->var_string = 'string_'.$english_string;
    //                 $language->english_string = $english_string;
    //                 $language->danish_string = $danishString;
                    
    //                 // Save the model instance
    //                 $language->save();
    //             }
    //         }
            
            
    //         if (empty($errors)) {
    //             // Return back with error messages
    //             return redirect()->back()->withErrors($errors);
    //         }

    //         $message = 'Data saved successfully';
    //         // Optionally, you can redirect the user after saving
    //         // return redirect()->back()->with('success', 'Data saved successfully.');
    //         return redirect()->back()->withSuccess($message);
        
    //     } catch (\Exception $e) {
    //         return redirect()->back()->withError($e->getMessage());
    //     }
        
    // }

    public function saveLanguage(Request $request)
{
    try {
        $input = $request->all();
        
        // Validate the incoming request data
        $request->validate([                
            'english_string.*' => 'required',
            'danish_string.*' => 'required',
        ]);
        
        // Array to store error messages for duplicate entries
        $errors = [];      
    
        // Loop through the var_string array and save each pair
        foreach ($request->english_string as $key => $english_string) {
            $danishString = $request->danish_string[$key];
            
            // Check if the english_string already exists
            $existingPair = LanguageMaster::where('english_string', $english_string)->first();

            if ($existingPair) {
                if (is_null($existingPair->danish_string)) {
                    // Update the danish_string if it is null
                    $existingPair->danish_string = $danishString;
                    $existingPair->save();
                } else {
                    $errors[] = "The pair with english_string '$english_string' already exists.";
                }
            } else {
                // Create a new LanguageMaster model instance
                $language = new LanguageMaster();
                $varString = 'string_' . str_replace(' ', '_', $english_string);
                // Assign the var_string, english_string, and danish_string to the appropriate attributes in the model
                $language->var_string = $varString;
                $language->english_string = $english_string;
                $language->danish_string = $danishString;
                
                // Save the model instance
                $language->save();
            }
        }
        
        if (empty($errors)) {
            // Return back with error messages
            return redirect()->back()->withErrors($errors);
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

