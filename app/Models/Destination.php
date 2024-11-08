<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    use HasFactory;

    protected $fillable = [
        'image',
        'discount',
        'name',
        'location',
        'price',
        'original_price',
        'rating',
        'date',
        'trip_advisor',
        'address',
    ];

    // Define the relationship with the Service model
    public function services()
    {
        return $this->hasMany(Service::class);
    }
}
