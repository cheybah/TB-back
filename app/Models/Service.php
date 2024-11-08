<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'destination_id',
        'name',
        'icon',
        'alt'
    ];

    // Define the inverse relationship with the Destination model
    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }
}
