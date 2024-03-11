<?php
 
namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Plans;
use Illuminate\View\View;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;

use Stripe\Stripe;
use Stripe\StripeClient;

use Auth;
use Hash;

class SubscriptionController extends Controller
{
    protected $stripe;

    public function __construct()
    {
        // Initialize the Stripe client instance in the constructor
        $getPlans = Plans::first();
        if($getPlans)
        {
            $stripeApiKey = $getPlans->test_or_live == '1' ? env('STRIPE_LIVE_SECRET') : env('STRIPE_TEST_SECRET');
            $this->stripe = new StripeClient($stripeApiKey);
        }
        else
        {
            return redirect()->route('admin.subscriptions');
        }
    }
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

        $subscriptionList = User::with('getLastSubscription')->where('user_role', 'user')->where('is_subscribe', '1')
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

    public function viewSubscription($id)
    {
        if (Auth::check()) 
        {
            if($id)
            {
                $userId = Crypt::decryptString($id);
                $subscriptionDetails = User::with('getLastSubscription', 'getSubscriptionDetails')->where('id', $userId)->first();

                $getCardDetails = '';
                $getAllInvoice = [];

                if($subscriptionDetails)
                {
                    $getCustomerDetails = $this->stripe->customers->retrieve($subscriptionDetails->stripe_customer_id);

                    if($getCustomerDetails)
                    {
                        $default_card = $getCustomerDetails->default_source;
                        $getCardDetails = $this->stripe->customers->retrieveSource($subscriptionDetails->stripe_customer_id, $default_card);
                    }

                    $getAllInvoice = $this->stripe->invoices->all([
                        "subscription" => $subscriptionDetails->stripe_subscription_id
                    ]);

                    if($getAllInvoice)
                    {
                        usort($getAllInvoice->data, function($a, $b) {
                            return $b->created - $a->created;
                        });
                    }
                }

                return view('admin.subscriptions.view', compact('userId', 'subscriptionDetails', 'getCardDetails', 'getAllInvoice'));
            }
            else
            {
                return redirect()->back()->with('error', 'Invalid user id.');
            }
        }
        else
        {
            return view('admin.login');
        }
    }
}