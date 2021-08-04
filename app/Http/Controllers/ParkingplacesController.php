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
        return response()->json(ParkingPlace::orderBy('id','desc')->paginate(5),200);
    }

    public function create(Request $request) {
        $rules = [
            'nom' => 'required|max:4',
            'price' => 'required|gt:0'
        ];

        $validator = \Validator::make($request->all(),$rules);
        if($validator->fails()) {
            return response()->json($validator->errors(),400);
        }

        $place = new ParkingPlace;

        $place->nom = $request->input('nom');
        $place->price = $request->input('price');

        if($place->save()) {
            return response()->json($place, 201);
        }

        return response()->json(["message"=>"Internal Server Error"], 500);
    }

    public function update(Request $request, $id) {
        $rules = [
            'nom' => 'required|max:4',
            'price' => 'required|gt:0'
        ];

        $result = $request->all();

        $validator = \Validator::make($result, $rules);
        if($validator->fails()) {
            return response()->json($validator->errors(),400);
        }

        $place = ParkingPlace::findOrFail($id);

        $input = array();

        $input['nom'] = $result['nom'];
        $input['price'] = $result['price'];

        $place->fill($input);

        if($place->update()) {
            return response()->json($place, 200);
        }
        
        return response()->json(["message"=>"Internal Server Error"], 500);
    }

    public function delete($id) {
        $place = ParkingPlace::findOrFail($id);

        if($place->delete()) {
            return response()->json('Deleted', 204);
        }
        return response()->json(["message"=>"Internal Server Error"],500);
    }
}
