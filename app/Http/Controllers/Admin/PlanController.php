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

class PlanController extends Controller
{
    /**
     * Show the profile for a given user.
     */
    public function plans()
    {
        if (Auth::check()) 
        {
            $getPlans = Plans::first();
            return view('admin.plans.plans', compact('getPlans'));
        }
        else
        {
            return view('admin.login');
        }
    }

    public function savePlans(Request $request)
    {
        try {
            $input = $request->all();

            $settingsId = isset($input['settings_id']) ? $input['settings_id'] : '';

            $planArr = [];
            $planArr['test_product_id'] = isset($input['test_product_id']) ? $input['test_product_id'] : '';
            $planArr['test_four_week_price_id'] = isset($input['test_four_week_price_id']) ? $input['test_four_week_price_id'] : '';
            $planArr['test_twelve_week_price_id'] = isset($input['test_twelve_week_price_id']) ? $input['test_twelve_week_price_id'] : '';
            $planArr['test_six_week_price_id'] = isset($input['test_six_week_price_id']) ? $input['test_six_week_price_id'] : '';

            $planArr['live_product_id'] = isset($input['live_product_id']) ? $input['live_product_id'] : '';
            $planArr['live_four_week_price_id'] = isset($input['live_four_week_price_id']) ? $input['live_four_week_price_id'] : '';
            $planArr['live_twelve_week_price_id'] = isset($input['live_twelve_week_price_id']) ? $input['live_twelve_week_price_id'] : '';
            $planArr['live_six_week_price_id'] = isset($input['live_six_week_price_id']) ? $input['live_six_week_price_id'] : '';

            $planArr['test_or_live'] = isset($input['test_or_live']) ? $input['test_or_live'] : '0';

            $message = "";
            if($settingsId)
            {
                Plans::where('id', $settingsId)->update($planArr);
                $message = "Plan updated successfully.";
            }
            else
            {
                Plans::create($planArr);
                $message = "Plan added successfully.";
            }

            return redirect()->route('admin.plans')->withSuccess($message);
            
        } catch (\Exception $e) {
            return redirect()->back()->withError($e->getMessage());
        }
    }
}