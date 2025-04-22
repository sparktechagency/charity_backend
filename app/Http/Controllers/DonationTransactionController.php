<?php

namespace App\Http\Controllers;

use App\Models\Transition;
use Exception;
use Illuminate\Http\Request;

class DonationTransactionController extends Controller
{
    public function getDonateTransation(Request $request)
    {
        try {
            $transactions = Transition::orderBy('id', 'DESC')->paginate($request->per_page);
            return $this->sendResponse($transactions, "Transactions retrieved successfully.");
        } catch (Exception $e) {
            return $this->sendError("An error occurred: " . $e->getMessage(), [], 500);
        }
    }
}
