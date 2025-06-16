<?php

namespace App\Http\Controllers;

use App\Http\Requests\BitContributeRequest;
use App\Http\Requests\ContributorStatusChangeRequest;
use App\Mail\ContributorOfAuctionWinnerMail;
use App\Models\Auction;
use App\Models\Contributor;
use App\Models\User;
use App\Notifications\BitContributorNotification;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ContributorController extends Controller
{
    public function bitContributor(BitContributeRequest $request)
    {
        try {
            $validated = $request->validated();
            $validated['user_id'] = $request->user()->id;
            $auction = Auction::find($validated['auction_id']);
            if (!$auction) {
                return $this->sendError("Auction not found.");
            }

            if ($validated['bit_online'] < $auction->start_budget) {
                return $this->sendError("Your bid must be at least $auction->start_budget.");
            }

            if ($validated['bit_online'] > $auction->end_budget) {
                return $this->sendError("Your bid must not exceed $auction->end_budget.");
            }
            $contributor = Contributor::create($validated);
            if($contributor){
                $admin = User::where('role', 'ADMIN')->first();
                $admin->notify(new BitContributorNotification($contributor));
            }
            return $this->sendResponse('Contributor successfully added.', $contributor);
        } catch (Exception $e) {
            return $this->sendError('An error occurred: ' . $e->getMessage(), [], 500);
        }
    }
    public function getContributor(Request $request)
    {
        try {
            $statusFilter = $request->input('status');
            $searchTerm = $request->input('search');
            $perPage = $request->input('per_page', 10);
            $latestContributorIds = Contributor::select(DB::raw('MAX(id) as id'))
                ->groupBy('auction_id');
            $contributors = Contributor::with(['auction', 'user'])
                ->whereIn('id', $latestContributorIds)
                ->when($statusFilter, function ($query) use ($statusFilter) {
                    $query->where('status', $statusFilter);
                })
                ->when($searchTerm, function ($query) use ($searchTerm) {
                    $query->whereHas('user', function ($q) use ($searchTerm) {
                        $q->where('full_name', 'like', "%{$searchTerm}%")
                            ->orWhere('email', 'like', "%{$searchTerm}%")
                            ->orWhere('contact_number', 'like', "%{$searchTerm}%");
                    });
                })
                ->orderByDesc('id')
                ->paginate($perPage);
            $mappedContributors = $contributors->getCollection()->map(function ($contributor) {
                return [
                    'id'              => $contributor->id,
                    'user_id'         => $contributor->user_id,
                    'auction_id'      => $contributor->auction_id,
                    'max_bit_online'  => $contributor->bit_online,
                    'status'          => $contributor->status,
                    'payment_status'  => $contributor->payment_status,
                    'user'            => [
                        'id'    => $contributor->user->id,
                        'name'  => $contributor->user->full_name,
                        'email' => $contributor->user->email,
                        'image' => $contributor->user->image,
                    ],
                    'auction'         => [
                        'id'             => $contributor->auction->id ?? null,
                        'title'          => $contributor->auction->title ?? null,
                        'description'    => $contributor->auction->description ?? null,
                        'name'           => $contributor->auction->name ?? null,
                        'email'          => $contributor->auction->email ?? null,
                        'donate_share'   => $contributor->auction->donate_share ?? null,
                        'contact_number' => $contributor->auction->contact_number ?? null,
                        'city'           => $contributor->auction->city ?? null,
                        'address'        => $contributor->auction->address ?? null,
                        'profile'        => $contributor->auction->profile ?? null,
                        'payment_type'   => $contributor->auction->payment_type ?? null,
                        'card_number'    => $contributor->auction->card_number ?? null,
                        'image'          => $contributor->auction->image ?? null,
                    ],
                    'created_at'      => $contributor->created_at->toDateTimeString(),
                ];
            });
            return $this->sendResponse([
                'unique_user_count' => $contributors->total(),
                'contributors'      => $mappedContributors,
                'pagination'        => [
                    'current_page' => $contributors->currentPage(),
                    'last_page'    => $contributors->lastPage(),
                    'total'        => $contributors->total(),
                    'per_page'     => $contributors->perPage(),
                ],
            ], "Contributors retrieved successfully.");
        } catch (Exception $e) {
            return $this->sendError('An error occurred: ' . $e->getMessage(), [], 500);
        }
    }
    public function contributorDetails(Request $request)
    {
        try {
            $contributor = Contributor::find($request->id);
            if (!$contributor) {
                return $this->sendError("Contributor not found.", [], 404);
            }
            $contributors = Contributor::where('user_id', $contributor->user_id)
                ->where('auction_id', $contributor->auction_id)
                ->where('status', 'Pending')
                ->orderByDesc('id')
                ->get();

            if ($contributors->isEmpty()) {
                return $this->sendError('No pending contributors found.', [], 404);
            }
            $maxBit = Contributor::where('user_id', $contributor->user_id)
                ->where('auction_id', $contributor->auction_id)
                ->max('bit_online');
            $user = $contributor->user;
            $auction = $contributor->auction;
            $userDetails = [
                'id'    => $user->id,
                'name'  => $user->full_name,
                'email' => $user->email,
                'image' => $user->image,
            ];
            $auctionDetails = [
                'id'             => $auction->id,
                'title'          => $auction->title,
                'description'    => $auction->description,
                'name'           => $auction->name,
                'email'          => $auction->email,
                'donate_share'   => $auction->donate_share,
                'contact_number' => $auction->contact_number,
                'city'           => $auction->city,
                'address'        => $auction->address,
                'profile'        => $auction->profile,
                'payment_type'   => $auction->payment_type,
                'card_number'    => $auction->card_number,
                'image'          => $auction->image,
            ];
            $contributorList = $contributors->map(function ($item) {
                return [
                    'contributor_id' => $item->id,
                    'status'         => $item->status,
                    'payment_status' => $item->payment_status,
                    'auction'        =>$item->auction,
                    'created_at'     => $item->created_at->toDateTimeString(),
                ];
            });
            $response = [
                'user'            => $userDetails,
                'auction'         => $auctionDetails,
                'max_bit_online'  => $maxBit,
                'contributors'    => $contributorList,
            ];
            return $this->sendResponse($response, 'Contributor details retrieved successfully.');
        } catch (Exception $e) {
            return $this->sendError('An error occurred: ' . $e->getMessage(), [], 500);
        }
    }
    public function singleContributorAuction(Request $request)
    {
        try {
            $auction = Auction::find($request->auction_id);
            if (!$auction) {
                return $this->sendError('Auction not found.', [], 404);
            }
            $contributor = $auction->contributor ?? null;

            return $this->sendResponse($auction, 'Contributor retrieved successfully.');
        } catch (Exception $e) {
            return $this->sendError('An error occurred: ' . $e->getMessage(), [], 500);
        }
    }

    public function contributorStatusChange(ContributorStatusChangeRequest $request)
    {
        try {
            $validated = $request->validated();
            $contributor = Contributor::with('auction')->find($validated['contributor_id']);
            if (!$contributor) {
                return $this->sendError("Contributor not found.");
            }
            $contributor->status = $validated['status'];
            $contributor->save();

            if ($contributor->status === 'winner') {
                Mail::to($contributor->email)->queue(new ContributorOfAuctionWinnerMail($contributor));
            }
            return $this->sendResponse($contributor, 'Contributor status updated successfully.');
        } catch (Exception $e) {
            return $this->sendError('An error occurred: ' . $e->getMessage(), [], 500);
        }
    }
}
