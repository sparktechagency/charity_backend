<?php

namespace App\Http\Controllers;

use App\Http\Requests\ServivorRequest;
use App\Mail\LuxuriesRetreatForAdminMail;
use App\Models\Transition;
use App\Models\Volunteer;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class SurvivorController extends Controller
{
    public function supportSurvivor(ServivorRequest $request)
    {
        try {
            $validated = $request->validated();
            if ($validated['donation_type'] === 'online_pay') {
                $lastInvoice = Transition::latest()->first();
                $invoiceNumber = 'INV-' . str_pad(($lastInvoice ? substr($lastInvoice->invoice, -4) : 0) + 1, 4, '0', STR_PAD_LEFT);
                $transition = Transition::create([
                    'invoice' => $invoiceNumber,
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'address' => $validated['address'],
                    'date' => now(),
                    'amount' => $validated['amount'],
                    'status' => 'Paid',
                ]);
                if ($transition->amount > 0) {
                    $volunteer = Volunteer::where('email', $validated['email'])->first();
                    if ($volunteer) {
                        $newDonatedAmount = $volunteer->donated + $transition->amount;
                        $volunteer->update(['donated' => $newDonatedAmount]);
                    }
                }
                return $this->sendResponse($transition, 'Donation processed successfully!');
            }
            else if ($validated['donation_type'] === 'luxurious') {
                $validator = Validator::make($request->all(), [
                    'item_name' => 'required|string',
                    'description' => 'nullable|string',
                    'images' => 'nullable|array|max:3',
                    'images.*' => 'mimes:jpeg,jpg,png,gif|max:10240',
                ]);
                if ($validator->fails()) {
                    return $this->sendError('Validation Errors', $validator->errors());
                }
                $sender_data = [
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'item_name' => $request->item_name,
                    'description' => $request->description,
                    'images' => [],
                ];
                if ($request->has('images')) {
                    foreach ($request->images as $image) {
                        $imageName = time() . '.' . $image->getClientOriginalExtension();
                        $image->move(public_path('uploads/donation'), $imageName);
                        $sender_data['images'][] =  url('uploads/donation/' . $imageName);
                    }
                }
                try {
                    Mail::to(env('MAIL_USERNAME'))->queue(new LuxuriesRetreatForAdminMail($sender_data));
                } catch (Exception $e) {
                    return $this->sendError('Mail sending failed. Please try again later.', [], 500);
                }
                return $this->sendResponse($sender_data,'Donation details have been sent successfully!');
            }
            else {
                return $this->sendError('Invalid donation type', ['donation_type' => 'Donation type does not exist.']);
            }
        } catch (Exception $e) {
            return $this->sendError("An error occurred: " . $e->getMessage(), [], 500);
        }
    }
}
