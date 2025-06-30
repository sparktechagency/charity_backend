<?php

namespace App\Http\Controllers;

use App\Http\Requests\CollectTableRequest;
use App\Http\Requests\ServivorRequest;
use App\Mail\InvoiceMail;
use App\Mail\LuxuriesRetreatForAdminMail;
use App\Models\Transition;
use App\Models\User;
use App\Models\Volunteer;
use App\Notifications\DonationNotification;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;

class SurvivorController extends Controller
{
    public function donateMoney(ServivorRequest $request)
    {
        try {
            $validated = $request->validated();
                if ($validated['donation_type'] === 'recurring') {
                    $allowedFrequencies = ['monthly', 'quantely', 'annually','single_payment'];
                    if (!in_array($validated['frequency'], $allowedFrequencies)) {
                        return $this->sendError("Frequency not found");
                    }
                }
                $invoiceNumber = 'INV-' . time() . rand(1000, 9999);
                $transition = Transition::create([
                    'invoice' => $invoiceNumber,
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'remark' => $validated['remark'] ?? null,
                    'amount' => $validated['amount'],
                    'donation_type' => $validated['donation_type'],
                    'payment_type' => $validated['payment_type'] ?? 'card',
                    'transaction_id' => $validated['transaction_id'] ?? 'card',
                    'frequency' => $validated['frequency'] ?? null,
                    'phone_number'=>$validated['phone_number'] ?? null,
                    'payment_gatway'=>$validated['payment_gatway'] ,
                    'payment_status' => 'Paid',
                ]);
                if ($transition->amount > 0) {
                    $volunteer = Volunteer::where('email', $validated['email'])->first();
                    if ($volunteer) {
                        $volunteer->increment('donated', $transition->amount);
                    }
                }
                if ($transition) {
                    Mail::to($validated['email'])->queue(new InvoiceMail($transition));
                }
                if($transition){
                    $admin = User::where('role', 'ADMIN')->first();
                    $admin->notify(new DonationNotification($transition));
                }
                return $this->sendResponse($transition, 'Donation processed successfully!');

        } catch (Exception $e) {
            return $this->sendError("An error occurred: " . $e->getMessage(), [], 500);
        }
    }
    public function collectTable(CollectTableRequest $request){
        try{
            $validated= $request->validated();
                $sender_data = [
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'item_name' => $validated['item_name'],
                    'description' => $validated['description'],
                    'donate_share' => $validated['donate_share'],
                    'images' => [],
                ];
                if ($request->has('images')) {
                    foreach ($request->images as $image) {
                        $imageName = time() . rand(10, 99) . '.' . $image->getClientOriginalExtension();
                        $image->move(public_path('uploads/donation'), $imageName);
                        $sender_data['images'][] = url('uploads/donation/' . $imageName);
                    }
                }
                Mail::to(env('MAIL_USERNAME'))->queue(new LuxuriesRetreatForAdminMail($sender_data));
                return $this->sendResponse($sender_data, 'Donation details have been sent successfully!');

        }catch(Exception $e){
            return $this->sendError("An error occored: ".$e->getMessage(),[],500);
        }
    }
}
