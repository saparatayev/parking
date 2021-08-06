<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParkingPlace extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'price',
        'nom'
    ];

    public function bookings() {
        return $this->hasMany(Booking::class);
    }

    public function scopeEmpty($query)
    {
        return $query->where('booked', 0);
    }
}
