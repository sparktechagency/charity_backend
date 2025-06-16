<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubscriberRequest;
use App\Models\Subscriber;
use App\Models\Volunteer;
use Exception;
use Illuminate\Http\Request;

class SubscriberController extends Controller
{
    public function subscriber(SubscriberRequest $request){
        try{
            $validated = $request->validated();
            $subscribe = Subscriber::create($validated);
            return $this->sendResponse($subscribe,"Successfully subscribed");
        }catch(Exception $e){
            return $this->sendError("An error occured: ".$e->getMessage(),[],500);
        }
    }
    public function getSubscriber(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');

            $query = Subscriber::query();

            if ($search) {
                $query->where('email', 'like', '%' . $search . '%');
            }

            $subscribers = $query->orderBy('id', 'desc')->paginate($perPage);

            $subscribers->getCollection()->transform(function ($subscriber) {
                return [
                    'id' => $subscriber->id,
                    'email' => $subscriber->email,
                    'subscribed_on' => $subscriber->created_at->format('Y-m-d H:i:s'),
                ];
            });

            return $this->sendResponse($subscribers, "Subscribers retrieved successfully.");
        } catch (\Exception $e) {
            return $this->sendError("An error occurred: " . $e->getMessage(), [], 500);
        }
    }
}
