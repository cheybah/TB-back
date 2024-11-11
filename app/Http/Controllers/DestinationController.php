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

        // Validate the incoming request data
        $validatedData = $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Adjust validation rules as needed
            'discount' => 'string|nullable',
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
            'services.*.icon' => 'required|mimes:svg|max:1024', // validate icon for each service
            'services.*.alt' => 'nullable|string',
        ]);

        try {
            // Handle the image file upload
            if ($request->hasFile('image')) {
                // Retrieve the file from the request
                $file = $request->file('image');

                // Store the image and get the path
                $imagePath = $file->store('images', 'public'); // store in 'public/images' folder

                // Assign the image path to validated data
                $validatedData['image'] = $imagePath;
            }


            // Save destination data in the database
            $destination = Destination::create($validatedData);

            foreach ($validatedData['services'] as $index => $serviceData) {
                // Handle the icon upload for each service
                if ($request->hasFile("services.$index.icon")) {
                    $iconFile = $request->file("services.$index.icon");
                    $iconPath = $iconFile->store('icons', 'public');

                    Log::info("Service $index icon path: $iconPath");


                    $destination->services()->create([
                        'name' => $serviceData['name'],
                        'icon' => $iconPath,  // Store icon path
                        'alt' => $serviceData['alt'] ?? 'Service Icon',
                    ]);
                } else {
                    Log::warning("Service $index icon file not found.");
                }
            }

                    // Log the services after creation
        Log::info('Services after creation: ', $destination->services->toArray());

            return response()->json([
                'success' => true,
                'destination' => $destination->load('services'), // Load services for complete response
            ], 201);
        } catch (\Exception $e) {
            Log::error("Error saving destination: " . $e->getMessage());
            return response()->json(['error' => 'Failed to save data.'], 500);
        }
    }

    public function show($id)
    {
        // Retrieve the destination by ID with associated services
        $destination = Destination::with('services')->find($id);

        if (!$destination) {
            return response()->json(['message' => 'Destination not found'], 404);
        }

        Log::info('Services for destination ID ' . $id . ': ', $destination->services->toArray());


        return response()->json($destination, 200);
    }


    public function fetch()
    {
        return Destination::with('services')->get(); // Retrieves all destinations with their services

    }
}
