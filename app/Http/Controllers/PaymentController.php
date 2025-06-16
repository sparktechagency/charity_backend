<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateIntentRequest;
use Illuminate\Support\Facades\Validator;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    public function createPaymentIntent(CreateIntentRequest $request)
    {
        $validated = $request->validated();
        Stripe::setApiKey(env('STRIPE_SECRET'));
        try {
            $paymentIntent = PaymentIntent::create([
                'amount' => $validated['amount'] * 100, // amount in pence for GBP
                'currency' => 'gbp',
                'payment_method' => $validated['payment_method'],
            ]);
            return $this->sendResponse($paymentIntent, 'Payment intent created successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Payment failed. ' . $e->getMessage(), [], 500);
        }
    }
    public function createPaypalPaymentIntent(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'currency' => 'required|string|max:3'
        ]);
        $clientId = config('services.paypal.client_id');
        $secret = config('services.paypal.secret');
        $baseUrl = config('services.paypal.base_url');
        $tokenResponse = Http::asForm()
            ->withBasicAuth($clientId, $secret)
            ->post("$baseUrl/v1/oauth2/token", [
                'grant_type' => 'client_credentials',
            ]);
        if (!$tokenResponse->successful()) {
            return response()->json(['error' => 'Unable to authenticate with PayPal.'], 500);
        }
        $accessToken = $tokenResponse->json()['access_token'];
        $orderResponse = Http::withToken($accessToken)
            ->post("$baseUrl/v2/checkout/orders", [
                'intent' => 'CAPTURE',
                'purchase_units' => [[
                    'amount' => [
                        'currency_code' => strtoupper($request->currency),
                        'value' => number_format($request->amount, 2, '.', ''),
                    ]
                ]],
                'application_context' => [
                    'return_url' => url('/paypal/success'),
                    'cancel_url' => url('/paypal/cancel'),
                ],
            ]);

        if (!$orderResponse->successful()) {
            return response()->json(['error' => 'Failed to create PayPal order.'], 500);
        }
        $orderData = $orderResponse->json();
        return response()->json([
            'status' => 'success',
            'order_id' => $orderData['id'],
            'approve_link' => collect($orderData['links'])->firstWhere('rel', 'approve')['href'] ?? null,
        ]);
    }
}
