<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuctionRequest;
use App\Models\Auction;
use App\Models\Contributor;
use Exception;
use Illuminate\Http\Request;

class AuctionController extends Controller
{
    public function getBitAuction(Request $request)
    {
        try {
            $auctions = Auction::where('status', 'Declared')->get();
            $data = $auctions->map(function ($auction) {
                $start = (float) $auction->start_budget;
                $end = (float) $auction->end_budget;
                $bit_prices = [];
                while ($start <= $end && $start > 0) {
                    $bit_prices[] = number_format($start, 2, '.', '');
                    $start *= 1.5;
                }
                $budget = $auction->start_budget . '-' . $auction->end_budget;
                $contributors = Contributor::where('auction_id', $auction->id)->get();
                $bits = $contributors->count();
                $maxBitOnline = Contributor::where('auction_id', $auction->id)->max('bit_online');
                return [
                    'id'             => $auction->id,
                    'title'          => $auction->title,
                    'description'    => $auction->description,
                    'image'          => $auction->image,
                    'donate_share'   => $auction->donate_share,
                    'name'           => $auction->name,
                    'email'          => $auction->email,
                    'contact_number' => $auction->contact_number,
                    'city'           => $auction->city,
                    'address'        => $auction->address,
                    'profile'        => $auction->profile,
                    'payment_type'   => $auction->payment_type,
                    'card_number'    => $auction->card_number,
                    'budget'         => $budget,
                    'duration'       => $auction->duration,
                    'status'         => $auction->status,
                    'created_at'     => $auction->created_at,
                    'updated_at'     => $auction->updated_at,
                    'bit_prices'     => $bit_prices ?? 0,
                    'total_bits'     => $bits ?? 0,
                    'max_bit_online' => $maxBitOnline ?? 0,
                ];
            });
            return $this->sendResponse($data, 'Auctions retrieved successfully.');
        } catch (Exception $e) {
            return $this->sendError('Error fetching auctions: ' . $e->getMessage(), [], 500);
        }
    }

    public function getAuction(Request $request)
    {
        try {
            $query = Auction::query();
            if ($request->filled('search')) {
                $searchTerm = $request->input('search');
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('title', 'like', '%' . $searchTerm . '%')
                      ->orWhere('description', 'like', '%' . $searchTerm . '%');
                });
            }
            if ($request->filled('status')) {
                $status = $request->input('status');
                $allowedStatuses = ['Pending', 'Declared', 'Remove'];

                if (in_array($status, $allowedStatuses)) {
                    $query->where('status', $status);
                } else {
                    return $this->sendError('Invalid status filter.', [], 400);
                }
            }
            $perPage = $request->input('per_page', 10);
            $auctions = $query->latest()->paginate($perPage);
            return $this->sendResponse($auctions, 'Auctions retrieved successfully.');
        } catch (Exception $e) {
            return $this->sendError('Error fetching auctions: ' . $e->getMessage(), [], 500);
        }
    }

    public function auction(AuctionRequest $request)
    {
        try {
            $validated = $request->validated();
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '-' . $image->getClientOriginalExtension();
                $imagePath = public_path('uploads/auctions/');
                $image->move($imagePath, $imageName);
                $imagePath = 'uploads/auctions/' . $imageName;
            } else {
                $imagePath = null;
            }
            if ($request->hasFile('profile')) {
                $profile = $request->file('profile');
                $profileName = time() . '.' . $profile->getClientOriginalExtension();
                $profilePath = public_path('uploads/auctions/profiles/');
                $profile->move($profilePath, $profileName);
                $profilePath = 'uploads/auctions/profiles/' . $profileName;
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
                'payment_type' => $validated['payment_type'] ?? null,
                'card_number' => $validated['card_number'] ?? null,
                'profile' => $profilePath,
            ]);
            return $this->sendResponse($auction, 'Application submitted successfully.');
        } catch (Exception $e) {
            return $this->sendError("An error occurred: " . $e->getMessage(), [], 500);
        }
    }
    public function asignBudget(Request $request)
    {
        try {
            $validated = $request->validate([
                'auction_id'   => 'required|exists:auctions,id',
                'start_budget' => 'required|numeric',
                'end_budget'   => 'required|numeric|gt:start_budget',
                'duration'     => 'required|integer',
            ]);
            $auction = Auction::find($request->auction_id);
            if(!$auction){
                return $this->sendError('Auction not found.');
            }
            $auction->start_budget = $validated['start_budget'];
            $auction->end_budget   = $validated['end_budget'];
            $auction->duration     = $validated['duration'];
            $auction->status       = 'Declared';
            $auction->save();
            return $this->sendResponse($auction, "Budget assigned successfully.");
        } catch (Exception $e) {
            return $this->sendError("An error occurred: " . $e->getMessage(), [], 500);
        }
    }
    public function auctionDetails(Request $request)
    {
        try {
            $auction = Auction::find($request->id);
            if(!$auction){
                return $this->sendError('Auction not found.');
            }
            return $this->sendResponse($auction, 'Auction details retrieved successfully.');
        } catch (Exception $e) {
            return $this->sendError("Failed to retrieve auction: " . $e->getMessage(), [], 500);
        }
    }
    public function updateAuction(AuctionRequest $request)
    {
        try {
            $validated = $request->validated();
            $auction = Auction::find($request->id);
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time()  . '.' . $image->getClientOriginalExtension();
                $imagePath = public_path('uploads/auctions/');
                $image->move($imagePath, $imageName);
                $auction->image = 'uploads/auctions/' . $imageName;
            }
            if ($request->hasFile('profile')) {
                $profile = $request->file('profile');
                $profileName = time() . '-' . uniqid() . '.' . $profile->getClientOriginalExtension();
                $profilePath = public_path('uploads/auctions/profiles/');
                $profile->move($profilePath, $profileName);
                $auction->profile = 'uploads/auctions/profiles/' . $profileName;
            }
            $auction->title = $validated['title'];
            $auction->description = $validated['description'];
            $auction->donate_share = $validated['donate_share'];
            $auction->name = $validated['name'];
            $auction->email = $validated['email'];
            $auction->contact_number = $validated['contact_number'];
            $auction->city = $validated['city'] ?? null;
            $auction->address = $validated['address'] ?? null;
            $auction->payment_type = $validated['payment_type'] ?? null;
            $auction->card_number = $validated['card_number'] ?? null;
            $auction->save();
            return $this->sendResponse($auction, 'Auction updated successfully.');
        } catch (Exception $e) {
            return $this->sendError("An error occurred: " . $e->getMessage(), [], 500);
        }
    }
    public function deleteAuction(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|integer|exists:auctions,id',
            ]);
            $auction = Auction::find($request->id);
            if (!$auction) {
                return $this->sendError('Auction not found.');
            }
            $auction->delete();
            return $this->sendResponse([], 'Auction deleted successfully.');
        } catch (Exception $e) {
            return $this->sendError('An error occurred: ' . $e->getMessage());
        }
    }

}
