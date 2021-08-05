<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;

class BookingController extends Controller
{
    public function index(Request $request) {
        if($request->get('customer_id')) {
            return view('bookings')->with([
                'customer_id' => $request->get('customer_id')
            ]);
        }
        return view('bookings');
    }

    public function getBookings(Request $request) {
        if($request->get('customer_id')) {
            $customerId = $request->get('customer_id');
           
            return response()->json(Booking::with('parkingPlace')
                ->where('customer_id',$customerId)
                ->orderBy('id','desc')
                ->paginate(5),200);
        }
        return response()->json(Booking::with(['customer','parkingPlace'])->orderBy('id','desc')->paginate(5),200);
    }
}
