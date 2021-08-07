<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\ParkingPlace;
use App\Models\Customer;
use \Carbon\Carbon;

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
        if($request->get('sort_date') && $request->get('sort_status')) {
            $date = $request->get('sort_date');
            $actual_date = 'actual_date_' . $request->get('sort_status');

            return response()->json(Booking::with(['customer','parkingPlace'])
                ->where($actual_date, $date)
                ->orderBy('id','desc')
                ->paginate(5), 200);
        }
        if($request->get('customer_id')) {
            $customerId = $request->get('customer_id');
           
            return response()->json(Booking::with('parkingPlace')
                ->where('customer_id',$customerId)
                ->orderBy('id','desc')
                ->paginate(5),200);
        }
        return response()->json(Booking::with(['customer','parkingPlace'])->orderBy('id','desc')->paginate(5),200);
    }

    public function bookPage(Request $request) {
        if($request->isMethod('post')) {
            $result = $request->all();

            $rules = [
                'car_model'=>'required',
                'nom'=>'required',
                'date_in'=>'required',
                'date_out'=>'required',
                'email' => 'required|email'
            ];

            // if email doesn't exist - customer is new
            if(!$request->get('customer_id')) {
                $rules['fio'] = 'required';
                $rules['phone'] = 'required';
            }

            $validator = \Validator::make($result, $rules);
            if($validator->fails()) {
                return redirect()->back()->withErrors($validator);
            }

            $customerId = false;
            $input = array();

            // if email doesn't exist - customer is new - create new custoemr
            if(!$request->get('customer_id')) {
                $input['fio'] = $result['fio'];
                $input['email'] = $result['email'];
                $input['phone'] = $result['phone'];
                $customer = new Customer();
                $customer->fill($input);
                if(!$customer->save()) {
                    return redirect()->back()->with('error','Ошибка добавления');
                }
                $customerId = $customer->id;
            } else {
                $customerId = $request->get('customer_id');
            }

            $parkingPlace = ParkingPlace::find($result['nom']);

            $input = array();
            $input['car_model'] = $result['car_model'];
            $input['parking_place_id'] = $result['nom'];
            $input['date_in'] = $result['date_in'];
            $input['date_out'] = $result['date_out'];
            $input['customer_id'] = $customerId;
            $input['price'] = $parkingPlace->price;

            // how much does it cost to book a parking place
            // for days between date_in and date_out
            $input['amount'] = Carbon::createFromDate($result['date_in'])
                ->diffInDays(Carbon::createFromDate($result['date_out'])) * $parkingPlace->price;

            $booking = new Booking();
            $booking->fill($input);
            if($booking->save()) {
                // when book, change the status of chosen parking place
                $parkingPlace->booked = 1;
                $parkingPlace->save();

                return redirect()->back()->with('status','Бронь добавлена');
            } else {
                return redirect()->back()->with('error','Ошибка добавления');
            }
        }
        return view('book_page')->with([
            'empty_parking_places' => ParkingPlace::empty()->limit(5)->get()
        ]);
    }

    public function changeStatus($id, Request $request) {
        $booking = Booking::find($id);
        if($request->get('actual_date_in') != '') {
            $booking->actual_date_in = $request->get('actual_date_in');
        }
        if($request->get('actual_date_out') != '') {
            $booking->actual_date_out = $request->get('actual_date_out');
        }
        
        if($booking->save()) {
            return response()->json($booking, 200);
        }
        return response()->json(["message"=>"Internal Server Error"], 500);
    }
    
}
