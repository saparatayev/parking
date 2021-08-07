<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomersController;
use App\Http\Controllers\ParkingplacesController;
use App\Http\Controllers\BookingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['auth'])->group(function() {
    Route::match(['get','post'], '/', [BookingController::class, 'bookPage'])
    ->name('bookPage');

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');


    // customers

    Route::get('/customers', [CustomersController::class, 'index'])
        ->name('customers');
        
    Route::match(['get','post'], '/customers/create', [CustomersController::class, 'create'])
        ->name('newCustomer');
        
    Route::match(['get','post'], '/customers/edit/{id}', [CustomersController::class, 'edit'])
        ->name('editCustomer');
        
    Route::delete('/customers/delete/{id}', [CustomersController::class, 'delete'])
        ->name('deleteCustomer');

    Route::get('/customers/find/{email}', [CustomersController::class, 'findByEmail'])
        ->name('findByEmail');


        // parking places
        
    Route::get('/parking-places', [ParkingplacesController::class, 'index'])
        ->name('parkingPlaces');

    Route::get('/parking-places/get-parking-places', [ParkingplacesController::class, 'getParkingPlaces'])
        ->name('getParkingPlaces');

    Route::post('/parking-places/create', [ParkingplacesController::class, 'create'])
        ->name('parkingPlacesNew');

    Route::post('/parking-places/{id}', [ParkingplacesController::class, 'update'])
        ->name('parkingPlacesNew');

    Route::delete('/parking-places/{id}', [ParkingplacesController::class, 'delete'])
        ->name('parkingPlacesDelete');
        
    Route::get('/parking-places/get-more', [ParkingplacesController::class, 'getMore'])
        ->name('getMoreParkingPlaces');

        
        //bookings 

    Route::get('/bookings', [BookingController::class, 'index'])
        ->name('bookings');
        
    Route::get('/bookings/get-bookings', [BookingController::class, 'getBookings'])
        ->name('getBookings');
        
    Route::post('/bookings/change-status/{id}', [BookingController::class, 'changeStatus'])
        ->name('changeStatus');
});



require __DIR__.'/auth.php';
