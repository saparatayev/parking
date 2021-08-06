<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'car_model',
        'parking_place_id',
        'date_in',
        'date_out',
        'customer_id',
        'price',
        'amount'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    
    public function parkingPlace()
    {
        return $this->belongsTo(ParkingPlace::class);
    }
}
