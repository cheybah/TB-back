<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Destination;
use App\Models\Service;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DestinationController extends Controller
{

    public function store(Request $request)
    {
        Log::info('Store method reached.');

        $validatedData = $request->validate([
            'image' => 'required|string',
            'discount' => 'required|string',
            'name' => 'required|string',
            'location' => 'required|string',
            'price' => 'required|string',
            'original_price' => 'required|string',
            'rating' => 'required|integer',
            'date' => 'required|string',
            'trip_advisor' => 'required|integer',
            'address' => 'required|string',
            'services' => 'required|array',
            'services.*.name' => 'required|string',
            'services.*.icon' => 'required|string',
            'services.*.alt' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $destination = Destination::create([
                'image' => $validatedData['image'],
                'discount' => $validatedData['discount'],
                'name' => $validatedData['name'],
                'location' => $validatedData['location'],
                'price' => $validatedData['price'],
                'original_price' => $validatedData['original_price'],
                'rating' => $validatedData['rating'],
                'date' => $validatedData['date'],
                'trip_advisor' => $validatedData['trip_advisor'],
                'address' => $validatedData['address'],
            ]);

            foreach ($validatedData['services'] as $serviceData) {
                $destination->services()->create([
                    'name' => $serviceData['name'],
                    'icon' => $serviceData['icon'],
                    'alt' => $serviceData['alt'] ?? 'Service Icon',
                ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Database error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to save data.'], 500);
        }

        return response()->json([
            'success' => true,
            'destination' => $destination,
        ], 201);
    }


public function show($id)
{
    // Retrieve the destination by ID with associated services
    $destination = Destination::with('services')->find($id);

    // If the destination is not found, return a 404 response
    if (!$destination) {
        return response()->json(['message' => 'Destination not found'], 404);
    }

    return response()->json($destination, 200);
}
}
