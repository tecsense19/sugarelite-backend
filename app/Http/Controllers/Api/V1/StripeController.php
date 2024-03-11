<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use App\Models\Plans;
use App\Models\UserSubscription;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;

use Stripe\Stripe;
use Stripe\Customer;
use Stripe\Subscription;
use Stripe\Token;
use Stripe\StripeClient;

use Validator;

class StripeController extends BaseController
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
            return $this->sendError('Invalid stripe key');
        }
    }

    public function createSubscription(Request $request)
    {
        try {
            $input = $request->all();

            $validator = Validator::make($input, [
                'user_id' => 'required',
                'price_id' => 'required',
                'stripe_token' => 'required',
                'plan_type' => 'required',
                'plan_price' => 'required'
            ]);
        
            if ($validator->fails()) {
                return $this->sendError($validator->errors()->first());
            }
        
            $getPlans = Plans::first();
            $getUser = User::where('id', $input['user_id'])->first();

            if($getPlans)
            {
                if($getUser)
                {
                    // $stripe = new \Stripe\StripeClient($stripeApiKey);

                    // // Create a card token from the provided card details
                    // $stripe->tokens->create([
                    //     'card' => [
                    //         'number' => $input['card_number'],
                    //         'exp_month' => $input['exp_month'],
                    //         'exp_year' => $input['exp_year'],
                    //         'cvc' => $input['cvc'],
                    //     ],
                    // ]);


                    // Create a card token from the provided card details
                    // $token = Token::create([
                    //     'card' => [
                    //         'number' => $input['card_number'],
                    //         'exp_month' => $input['exp_month'],
                    //         'exp_year' => $input['exp_year'],
                    //         'cvc' => $input['cvc'],
                    //     ],
                    // ]);

                    // Get exesting stripe customer id
                    $stripeCustomerId = $getUser->stripe_customer_id ? $getUser->stripe_customer_id : '';
                    if(!$stripeCustomerId)
                    {
                        // Create a customer with the card token
                        $customer = $this->stripe->customers->create([
                            'name' => $getUser->username,
                            'email' => $getUser->email,
                            'source' => $input['stripe_token'], // Use the card token as the source
                            "test_clock" => "clock_1Ot4etBx8CTGPFp9MUyKv93S"
                        ]);

                        User::where('id', $input['user_id'])->update(['stripe_customer_id' => $customer->id]);

                        $stripeCustomerId = $customer->id;
                    }

                    // Create a subscription for the customer
                    $subscription = $this->stripe->subscriptions->create([
                        'customer' => $stripeCustomerId,
                        'items' => [
                            [
                                'price' => $input['price_id'], // Replace with your actual price ID
                            ],
                        ]
                    ]);

                    $subscriptionArr = [];
                    $subscriptionArr['user_id'] = $input['user_id'];
                    $subscriptionArr['price_id'] = $input['price_id'];
                    $subscriptionArr['plan_type'] = $input['plan_type'];
                    $subscriptionArr['plan_price'] = $input['plan_price'];
                    $subscriptionArr['upgrade_downgrade'] = 'Create';
                    UserSubscription::create($subscriptionArr);

                    $currentDate = date('Y-m-d H:i:s');

                    $subArr = [];
                    $subArr['is_subscribe'] = '1';
                    $subArr['stripe_subscription_id'] = $subscription->id;
                    $subArr['subscription_item_id'] = $subscription->items->data[0]->id;
                    $subArr['subscription_start_date'] = date('Y-m-d H:i:s', strtotime($currentDate));
                    if($input['plan_type'] == '4week') {
                        $subArr['subscription_end_date'] = date('Y-m-d H:i:s', strtotime($currentDate . ' +4 weeks'));
                        $subArr['next_subscription_date'] = date('Y-m-d H:i:s', strtotime($currentDate . ' +4 weeks'));
                    } elseif ($input['plan_type'] == '6week') {
                        $subArr['subscription_end_date'] = date('Y-m-d H:i:s', strtotime($currentDate . ' +6 weeks'));
                        $subArr['next_subscription_date'] = date('Y-m-d H:i:s', strtotime($currentDate . ' +6 weeks'));
                    } elseif ($input['plan_type'] == '12week') {
                        $subArr['subscription_end_date'] = date('Y-m-d H:i:s', strtotime($currentDate . ' +12 weeks'));
                        $subArr['next_subscription_date'] = date('Y-m-d H:i:s', strtotime($currentDate . ' +12 weeks'));
                    }
                    $subArr['subscription_cancel_date'] = "";
                    $subArr['is_subscription_cancel'] = "0";

                    User::where('id', $input['user_id'])->update($subArr);

                    return $this->sendResponse($subscription, 'Subscriptioin created successfully.');
                }
                else
                {
                    return $this->sendError('Invalid user id.');    
                }
            }
            else
            {
                return $this->sendError('Invalid plan id.');    
            }
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function startStopSubscription(Request $request)
    {
        try {
            $input = $request->all();

            $validator = Validator::make($input, [
                'user_id' => 'required',
                'start_stop' => 'required'
            ]);

            if ($validator->fails()) {
                return $this->sendError($validator->errors()->first());
            }

            $getPlans = Plans::first();
            $getUser = User::where('id', $input['user_id'])->first();
            if($getPlans)
            {
                if($getUser)
                {
                    $startStop = $input['start_stop'] == 'start' ? false : true;

                    // Subscription start and stop
                    $subscription = $this->stripe->subscriptions->update($getUser->stripe_subscription_id, [
                        'cancel_at_period_end' => $startStop,
                    ]);

                    $updateArr = [];
                    $updateArr['is_subscription_stop'] = $input['start_stop'] == 'start' ? '0' : '1';

                    User::where('id', $input['user_id'])->update($updateArr);

                    return $this->sendResponse($subscription, 'Subscription '. $input['start_stop'] .' successfully.');
                }
                else
                {
                    return $this->sendError('Invalid user id.');
                }
            }
            else
            {
                return $this->sendError('Invalid plan id.');
            }

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function cancelSubscription(Request $request)
    {
        try {
            $input = $request->all();

            $validator = Validator::make($input, [
                'user_id' => 'required',
                'at_cancel' => 'required'
            ]);

            if ($validator->fails()) {
                return $this->sendError($validator->errors()->first());
            }

            $getPlans = Plans::first();
            $getUser = User::where('id', $input['user_id'])->first();
            if($getPlans)
            {
                if($getUser)
                {
                    if($input['at_cancel'] == 'yes')
                    {
                        // Subscription cancel
                        $subscription = $this->stripe->subscriptions->cancel($getUser->stripe_subscription_id, []);

                        $updateArr = [];
                        $updateArr['is_subscription_cancel'] = '1';
                        $updateArr['subscription_cancel_date'] = date('Y-m-d H:i:s');

                        User::where('id', $input['user_id'])->update($updateArr);

                        return $this->sendResponse($subscription, 'Subscription cancel successfully.');
                    }
                    else
                    {
                        return $this->sendError('Invalid at cancel.');
                    }
                }
                else
                {
                    return $this->sendError('Invalid user id.');
                }
            }
            else
            {
                return $this->sendError('Invalid plan id.');
            }

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function upgradeDowngrade(Request $request)
    {
        try {
            $input = $request->all();

            $validator = Validator::make($input, [
                'user_id' => 'required',
                'price_id' => 'required',
                'plan_type' => 'required',
                'plan_price' => 'required',
                'upgrade_downgrade' => 'required'
            ]);

            if ($validator->fails()) {
                return $this->sendError($validator->errors()->first());
            } 

            $getPlans = Plans::first();
            $getUser = User::where('id', $input['user_id'])->first();
            if($getPlans)
            {
                if($getUser)
                {
                    // Subscription upgrades downgrades
                    $subscription = $this->stripe->subscriptions->update($getUser->stripe_subscription_id, [
                        'items' => [
                            [
                                'id' => $getUser->subscription_item_id,
                                'price' => $input['price_id'],
                            ]
                        ],
                    ]);

                    $subscriptionArr = [];
                    $subscriptionArr['user_id'] = $input['user_id'];
                    $subscriptionArr['price_id'] = $input['price_id'];
                    $subscriptionArr['plan_type'] = $input['plan_type'];
                    $subscriptionArr['plan_price'] = $input['plan_price'];
                    $subscriptionArr['upgrade_downgrade'] = $input['upgrade_downgrade'];
                    UserSubscription::create($subscriptionArr);

                    return $this->sendResponse($subscription, 'Subscription cancel successfully.');
                }
                else
                {
                    return $this->sendError('Invalid user id.');
                }
            }
            else
            {
                return $this->sendError('Invalid plan id.');
            }
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function createWebhook(Request $request)
    {
        try {
            $input = $request->all();

            // Subscription upgrades downgrades
            $webhookEndpoint = $this->stripe->webhookEndpoints->create([
                'enabled_events' => ['customer.subscription.updated'],
                'url' => url('/').'/subscription/update/webhook',
            ]);

            return $this->sendResponse($webhookEndpoint, 'Webhook end point created successfully.');
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function createTestClock(Request $request)
    {
        try {
            $input = $request->all();
            
            $testClock = $this->stripe->testHelpers->testClocks->create(['frozen_time' => time()]);

            return $this->sendResponse($testClock, 'Webhook end point created successfully.');
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
}
