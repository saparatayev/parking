<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomersController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::get('/customers', [CustomersController::class, 'index'])
    ->middleware(['auth'])->name('customers');
    
Route::match(['get','post'], '/customers/create', [CustomersController::class, 'create'])
    ->middleware(['auth'])->name('newCustomer');
    
Route::match(['get','post'], '/customers/edit/{id}', [CustomersController::class, 'edit'])
    ->middleware(['auth'])->name('editCustomer');
    
Route::delete('/customers/delete/{id}', [CustomersController::class, 'delete'])
    ->middleware(['auth'])->name('deleteCustomer');

require __DIR__.'/auth.php';
