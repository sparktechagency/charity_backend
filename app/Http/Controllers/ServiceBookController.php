<?php

namespace App\Http\Controllers;

use App\Http\Requests\ServiceBookRequest;
use App\Mail\BookConfirmationMail;
use App\Models\ServiceBook;
use App\Models\User;
use App\Notifications\ServiceBookNotificaiton;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ServiceBookController extends Controller
{
    public function getBook()
    {
        try {
            $bookings = ServiceBook::latest()->paginate(10);
            return $this->sendResponse($bookings, 'Bookings retrieved successfully.');
        } catch (Exception $e) {
            return $this->sendError('Error fetching bookings.'.$e->getMessage(),[],500);
        }
    }
    public function createBook(ServiceBookRequest $request)
    {
        try {
            $validated = $request->validated();
            $booking = ServiceBook::create($validated);

            $admin = User::where('role', 'ADMIN')->first();
            $admin->notify(new ServiceBookNotificaiton($booking));

            return $this->sendResponse($booking, 'Booking created successfully.');
        } catch (Exception $e) {
            return $this->sendError('Failed to create booking. ' . $e->getMessage(), [], 500);
        }
    }

    public function bookStatus(Request $request)
    {
        try {
            $validated = $request->validate([
                'book_id' => 'required|exists:service_books,id',
                'book_status' => 'required|in:Accepted,Pending,Rejected',
            ]);
            $booking = ServiceBook::findOrFail($validated['book_id']);
            $booking->update(['book_status' => $validated['book_status']]);
            Mail::to($booking->email)->send(new BookConfirmationMail($booking));
            return $this->sendResponse($booking, 'Booking status updated successfully.');
        } catch (Exception $e) {
            return $this->sendError('Error updating status: ' . $e->getMessage(), [], 500);
        }
    }
}
