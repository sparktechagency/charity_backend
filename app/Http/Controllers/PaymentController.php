<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateIntentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use GuzzleHttp\Client;
class PaymentController extends Controller
{
    public function createPaymentIntent(CreateIntentRequest $request)
    {
        $validated = $request->validated();
        Stripe::setApiKey(config('services.stripe.secret'));
        try {
            $paymentIntent = PaymentIntent::create([
                'amount' => $validated['amount'],
                'currency' => 'usd',
                'payment_method' => $validated['payment_method'],
            ]);
            return $this->sendResponse($paymentIntent, 'Payment intent created successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Payment failed.'. $e->getMessage(),[], 500);
        }
    }

    public function createPaypalOrder(Request $request)
    {

    }

}
