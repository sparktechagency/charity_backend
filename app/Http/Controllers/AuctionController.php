<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuctionRequest;
use App\Models\Auction;
use Exception;
use Illuminate\Http\Request;

class AuctionController extends Controller
{
    public function auction(AuctionRequest $request)
    {
        try {
            $validated = $request->validated();
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '-' . $image->getClientOriginalExtension();
                $imagePath = public_path('auctions/images');
                $image->move($imagePath, $imageName);
                $imagePath = 'auctions/images/' . $imageName;
            } else {
                $imagePath = null;
            }
            if ($request->hasFile('profile')) {
                $profile = $request->file('profile');
                $profileName = time() . '-' . $profile->getClientOriginalExtension();
                $profilePath = public_path('auctions/profiles');
                $profile->move($profilePath, $profileName);
                $profilePath = 'auctions/profiles/' . $profileName;
            } else {
                $profilePath = null;
            }
            $auction = Auction::create([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'image' => $imagePath,
                'donate_share' => $validated['donate_share'],
                'name' => $validated['name'],
                'email' => $validated['email'],
                'contact_number' => $validated['contact_number'],
                'city' => $validated['city'] ?? null,
                'address' => $validated['address'] ?? null,
                'payment_type' => $validated['payment_type'],
                'card_number' => $validated['card_number'],
                'profile' => $profilePath,
            ]);
            return $this->sendResponse($auction, 'Application submitted successfully.');
        } catch (Exception $e) {
            return $this->sendError("An error occurred: " . $e->getMessage(), [], 500);
        }
    }
}
