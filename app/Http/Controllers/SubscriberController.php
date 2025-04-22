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
            $subscribers = Subscriber::orderBy('id', 'desc')->paginate($request->per_page ?? 10);
            $data = $subscribers->getCollection()->map(function ($subscribe) {
                $volunteer = Volunteer::where('email', $subscribe->email)->select('name')->first();
                return [
                    'id' => $subscribe->id,
                    'name' => $volunteer->name ?? 'N/A',
                    'email' => $subscribe->email,
                    'subscribed_on' => $subscribe->created_at->format('Y-m-d H:i:s'),
                ];
            });
            $subscribers->setCollection($data);
            return $this->sendResponse($subscribers, "Subscribers retrieved successfully.");
        } catch (Exception $e) {
            return $this->sendError("An error occurred: " . $e->getMessage(), [], 500);
        }
    }
}
