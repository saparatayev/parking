<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\ParkingPlace;
use App\Models\Customer;

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

    protected function validateFields($rules, $data) {
        
    }
    
}
