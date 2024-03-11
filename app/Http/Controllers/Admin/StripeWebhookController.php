<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Event;
use App\Models\User;
use App\Models\Plans;
use Stripe\Stripe;
use Stripe\StripeClient;
use App\Http\Controllers\Controller as Controller;

class StripeWebhookController extends Controller
{
    public function handleSubscriptionUpdated(Request $request)
    {
        // Parse the incoming webhook event
        $payload = $request->getContent();

        // Log the payload
        Log::info('Webhook Payload', ['payload' => json_decode($payload, true)]);

        if($payload)
        {
            $event = json_decode($payload, true);

            if($event)
            {
                $userData = [];
                $userData['subscription_end_date'] = date('Y-m-d', $event['data']['object']['current_period_end']);
                $userData['next_subscription_date'] = date('Y-m-d', $event['data']['object']['current_period_end']);

                User::where('stripe_subscription_id', $event['data']['object']['id'])->update($userData);
            }

            return response()->json(['success' => true, 'message' => 'Webhook received successfully.']);
        }
        else
        {
            return response()->json(['success' => true, 'message' => 'Webhook received fail.']);
        }
    }
}
