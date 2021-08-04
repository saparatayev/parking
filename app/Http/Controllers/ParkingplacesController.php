<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ParkingPlace;

class ParkingplacesController extends Controller
{
    public function index() {
        return view('parking_places');
    }

    public function getParkingPlaces() {
        return response()->json(ParkingPlace::paginate(5),200);
    }
}
