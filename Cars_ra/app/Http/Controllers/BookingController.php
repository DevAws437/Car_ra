<?php
namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Car;
use Illuminate\Support\Facades\Notification;
use App\Notifications\BookingNotification;

class BookingController extends Controller
{
    //show all bookings
    public function index()
    {
        $bookings = Booking::with(['user', 'car'])->get();
        return response()->json($bookings, 200);
    }

    public function create(Request $request)
        {
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
                'car_id' => 'required|exists:cars,id',
                'start_date' => 'required|date|after_or_equal:today',
                'end_date' => 'required|date|after:start_date',
                'location' => 'required|string',
                'status' => 'nullable|string|in:pending,confirmed,cancelled',
            ]);


            $booking = Booking::create($validated);
            // إشعار تأكيد الحجز
            $user = User::find($booking->user_id);

            if ($user) {
                Notification::send($user, new BookingNotification($booking, 'confirmed'));
            }

            if ($booking->user) {
                Notification::send($booking->user, new BookingNotification($booking, 'confirmed'));
            }



            return response()->json([
                'message' => 'تم إنشاء الحجز بنجاح',
                'booking' => $booking,
            ], 201);
        }



    public function show($id)
    {
        $booking = Booking::with(['user', 'car'])->find($id);
        if (!$booking) {
            return response()->json(['message' => 'الحجز غير موجود'], 404);
        }
        return response()->json($booking, 200);
    }




    // delete booking
    public function cancel($id)
    {
        $booking = Booking::find($id);
        if (!$booking) {
            return response()->json(['message' => 'الحجز غير موجود'], 404);
        }

        // إشعار إلغاء الحجز
        // $user = User::find($booking->user_id);
        // Notification::send($user, new BookingNotification($booking, 'cancelled'));

        return response()->json(['message' => 'تم إلغاء الحجز بنجاح'], 200);
    }
}

