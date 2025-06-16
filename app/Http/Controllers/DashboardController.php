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
            $currentYear = now()->year;
            $lastYear = now()->subYear()->year;

            $monthNames = [
                1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
                5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
                9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
            ];

            // Actual data from database
            $approvedVolunteers = Volunteer::where('status', 'Approved')->count();
            $declaredAuctions = Auction::where('status', 'Declared')->count();
            $uniqueContributors = Contributor::distinct('user_id')->count();
            $podcastsCount = PodcastStore::count();
            $subscribersCount = Subscriber::count();
            $teamsCount = Team::count();
            $paidTransitions = Transition::where('payment_status', 'Paid')->sum('amount');
            $acceptedServiceBooks = ServiceBook::where('book_status', 'Accepted')->count();

            // Monthly Paid Transitions for This Year
            $monthlyThisYear = Transition::where('payment_status', 'Paid')
                ->whereYear('created_at', $currentYear)
                ->selectRaw('MONTH(created_at) as month, SUM(amount) as total')
                ->groupBy('month')
                ->pluck('total', 'month');

            // Monthly Paid Transitions for Last Year
            $monthlyLastYear = Transition::where('payment_status', 'Paid')
                ->whereYear('created_at', $lastYear)
                ->selectRaw('MONTH(created_at) as month, SUM(amount) as total')
                ->groupBy('month')
                ->pluck('total', 'month');

            // Prepare monthly data with month name as key
            $thisYearData = [];
            $lastYearData = [];

            foreach ($monthNames as $month => $name) {
                $thisYearData[] = [
                    'month' => $name,
                    'data' => (float)($monthlyThisYear[$month] ?? 0) 
                ];

                $lastYearData[] = [
                    'month' => $name,
                    'data' => (float)($monthlyLastYear[$month] ?? 0)
                ];
            }


            // Final response structure
            $dashboardData = [
                'approvedVolunteers' => $approvedVolunteers,
                'declaredAuctions' => $declaredAuctions,
                'uniqueContributors' => $uniqueContributors,
                'podcastsCount' => $podcastsCount,
                'subscribersCount' => $subscribersCount,
                'teamsCount' => $teamsCount,
                'paidTransitions' => $paidTransitions,
                'acceptedServiceBooks' => $acceptedServiceBooks,
                'thisYear' => $thisYearData,
                'lastYear' => $lastYearData
            ];

            return $this->sendResponse($dashboardData, "Dashboard data retrieved successfully");
        } catch (Exception $e) {
            return $this->sendError("An error occurred: " . $e->getMessage(), [], 500);
        }
    }






}
