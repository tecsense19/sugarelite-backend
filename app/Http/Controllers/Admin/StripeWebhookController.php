<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Event;
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
        Log::info('Webhook Payload', ['payload' => $payload]);

        if($payload)
        {
            $event = Event::constructFrom(json_decode($payload, true));

            // Verify the event signature
            try {
                $event->validate();
            } catch (\UnexpectedValueException $e) {
                Log::error('Webhook signature verification failed.', ['exception' => $e]);
                return response()->json(['error' => 'Signature verification failed.'], 400);
            }

            // Handle the event
            if ($event->type === 'customer.subscription.updated') {
                $subscription = $event->data->object;
                // Update your database or take necessary actions
                // For example:
                // updateSubscription($subscription);
            }

            return response()->json(['success' => true, 'message' => 'Webhook received successfully.']);
        }
        else
        {
            return response()->json(['success' => true, 'message' => 'Webhook received fail.']);
        }
    }
}
