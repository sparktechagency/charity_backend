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
    public function getBook(Request $request)
    {
        try {
            $bookings = ServiceBook::latest()->paginate($request->per_page);
            return $this->sendResponse($bookings, 'Bookings retrieved successfully.');
        } catch (Exception $e) {
            return $this->sendError('Error fetching bookings.'.$e->getMessage(),[],500);
        }
    }
    public function getAvailableBookingTime(Request $request)
    {
        try {
            $today = now()->toDateString();
            $bookings = ServiceBook::whereDate('book_date', $today)->get(['book_time', 'book_date']);

            if ($bookings->isEmpty()) {
                return $this->sendError('No bookings available for today.', [], 404);
            }

            return $this->sendResponse([
                'book_time' => $bookings->pluck('book_time')->unique()->values(),
                'book_date' => $bookings->pluck('book_date')->unique()->values(),
            ], 'Booking times retrieved successfully.');
        } catch (Exception $e) {
            return $this->sendError('Error fetching bookings. ' . $e->getMessage(), [], 500);
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
