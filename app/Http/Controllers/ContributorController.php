<?php

namespace App\Http\Controllers;

use App\Http\Requests\BitContributeRequest;
use App\Http\Requests\ContributorStatusChangeRequest;
use App\Models\Contributor;
use App\Models\User;
use App\Notifications\BitContributorNotification;
use Exception;
use Illuminate\Http\Request;

class ContributorController extends Controller
{
    public function bitContributor(BitContributeRequest $request)
    {
        try {
            $validated = $request->validated();
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
            $contributorsQuery = Contributor::with('auction')->latest();
            if ($statusFilter) {
                $contributorsQuery->where('status', $statusFilter);
            }
            if ($searchTerm) {
                $contributorsQuery->where(function ($query) use ($searchTerm) {
                    $query->where('name', 'like', '%' . $searchTerm . '%')
                        ->orWhere('email', 'like', '%' . $searchTerm . '%')
                        ->orWhere('contact_number', 'like', '%' . $searchTerm . '%');
                });
            }
            $contributors = $contributorsQuery->paginate($perPage);
            $uniqueContributors = $contributors->getCollection()->unique('email')->values();
            $uniqueUserCount = $uniqueContributors->count();
            $mappedContributors = $uniqueContributors->map(function ($contributor, $index) use ($contributors) {
                $related = $contributors->getCollection()->where('email', $contributor->email);
                $maxBit = $related->max('bit_online');
                return [
                    'id'              => $index + 1,
                    'contributor_id'  => $contributor->id,
                    'name'            => $contributor->name,
                    'email'           => $contributor->email,
                    'contact_number'  => $contributor->contact_number,
                    'max_bit_online'  => $maxBit,
                    'payment_type'    => $contributor->payment_type,
                    'card_number'     => $contributor->card_number,
                    'status'          => $contributor->status,
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
                'unique_user_count' => $uniqueUserCount,
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
    public function contributorStatusChange(ContributorStatusChangeRequest $request)
    {
        try {
            $validated = $request->validated();
            $contributor = Contributor::find($validated['contributor_id']);
            if (!$contributor) {
                return $this->sendError("Contributor not found.");
            }
            $contributor->status = $validated['status'];
            $contributor->save();
            return $this->sendResponse($contributor, 'Contributor status updated successfully.');
        } catch (Exception $e) {
            return $this->sendError('An error occurred: ' . $e->getMessage(), [], 500);
        }
    }
}
