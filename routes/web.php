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

Route::match(['get','post'], '/', [BookingController::class, 'bookPage'])
    ->middleware(['auth'])->name('bookPage');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');


// customers

Route::get('/customers', [CustomersController::class, 'index'])
    ->middleware(['auth'])->name('customers');
    
Route::match(['get','post'], '/customers/create', [CustomersController::class, 'create'])
    ->middleware(['auth'])->name('newCustomer');
    
Route::match(['get','post'], '/customers/edit/{id}', [CustomersController::class, 'edit'])
    ->middleware(['auth'])->name('editCustomer');
    
Route::delete('/customers/delete/{id}', [CustomersController::class, 'delete'])
    ->middleware(['auth'])->name('deleteCustomer');

Route::get('/customers/find/{email}', [CustomersController::class, 'findByEmail'])
    ->middleware(['auth'])->name('findByEmail');


    // parking places
    
Route::get('/parking-places', [ParkingplacesController::class, 'index'])
    ->middleware(['auth'])->name('parkingPlaces');

Route::get('/parking-places/get-parking-places', [ParkingplacesController::class, 'getParkingPlaces'])
    ->middleware(['auth'])->name('getParkingPlaces');

Route::post('/parking-places/create', [ParkingplacesController::class, 'create'])
    ->middleware(['auth'])->name('parkingPlacesNew');

Route::post('/parking-places/{id}', [ParkingplacesController::class, 'update'])
    ->middleware(['auth'])->name('parkingPlacesNew');

Route::delete('/parking-places/{id}', [ParkingplacesController::class, 'delete'])
    ->middleware(['auth'])->name('parkingPlacesDelete');
    
Route::get('/parking-places/get-more', [ParkingplacesController::class, 'getMore'])
    ->name('getMoreParkingPlaces');

    
    //bookings 

Route::get('/bookings', [BookingController::class, 'index'])
    ->middleware(['auth'])->name('bookings');
    
Route::get('/bookings/get-bookings', [BookingController::class, 'getBookings'])
    ->middleware(['auth'])->name('getBookings');

require __DIR__.'/auth.php';
