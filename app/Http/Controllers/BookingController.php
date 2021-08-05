<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;

class BookingController extends Controller
{
    public function index() {
        return view('bookings');
    }

    public function getBookings() {
        return response()->json(Booking::with(['customer','parkingPlace'])->orderBy('id','desc')->paginate(5),200);
    }
}
