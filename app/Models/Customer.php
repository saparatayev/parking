<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'fio',
        'email',
        'phone',
    ];

    public function bookings() {
        return $this->hasMany(Booking::class);
    }
}
