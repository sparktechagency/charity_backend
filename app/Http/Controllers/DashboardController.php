<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Models\Contributor;
use App\Models\PodcastStore;
use App\Models\ServiceBook;
use App\Models\Subscriber;
use App\Models\Team;
use App\Models\Transition;
use App\Models\Volunteer;
use Exception;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        try {
            $approvedVolunteers = Volunteer::where('status', 'Approved')->count();
            $declaredAuctions = Auction::where('status', 'Declared')->count();
            $uniqueContributors = Contributor::distinct('email')->count();
            $podcastsCount = PodcastStore::count();
            $subscribersCount = Subscriber::count();
            $teamsCount = Team::count();
            $paidTransitions = Transition::where('payment_status', 'Paid')->sum('amount'); // Assuming 'amount' is the column name
            $acceptedServiceBooks = ServiceBook::where('book_status', 'Accepted')->count();

            $monthlyTransitions = Transition::where('payment_status', 'Paid')
                ->selectRaw('MONTH(created_at) as month, SUM(amount) as total_amount')
                ->groupBy('month')
                ->orderBy('month')
                ->get();
            $monthNames = [
                1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June',
                7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
            ];
            $monthlyData = [];
            for ($i = 1; $i <= 12; $i++) {
                $monthlyData[$monthNames[$i]] = 0;
            }
            foreach ($monthlyTransitions as $transition) {
                $monthlyData[$monthNames[$transition->month]] = $transition->total_amount;
            }
            $dashboardData = [
                'approvedVolunteers' => $approvedVolunteers,
                'declaredAuctions' => $declaredAuctions,
                'uniqueContributors' => $uniqueContributors,
                'podcastsCount' => $podcastsCount,
                'subscribersCount' => $subscribersCount,
                'teamsCount' => $teamsCount,
                'paidTransitions' => $paidTransitions,
                'acceptedServiceBooks' => $acceptedServiceBooks,
                'monthlyTransitions' => $monthlyData,
            ];
            return $this->sendResponse($dashboardData, "Dashboard retrieved successfully");
        } catch (Exception $e) {
            return $this->sendError("An error occurred: " . $e->getMessage(), [], 500);
        }
    }
}
