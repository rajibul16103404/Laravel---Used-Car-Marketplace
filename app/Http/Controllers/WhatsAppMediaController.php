<?php 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Modules\Admin\CarLists\Models\Carlist;
use Modules\Auth\Models\Auth;

class WhatsAppMediaController extends Controller
{
    public function downloadImage(Request $request)
    {
        $request->validate([
            'media_id' => 'required|string',
            'car_id' => 'required|string',
            'phone' => 'required|string',
        ]);

        $mediaId = $request->input('media_id');
        $phone = $request->input('phone');
        $accessToken = env('WhatsappImageAccessToken');
        $saveDir = 'public/WhatsappImages'; // Storage directory

        // Check if the phone number exists in the auth table
        $user = Auth::where('phone', $phone)->first();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {
            // Check if the car entry exists
            $findCar = Carlist::find($request->car_id);
            if (!$findCar) {
                return response()->json(['error' => 'Car not found'], 404);
            }

            $file_type = Http::withToken($accessToken)->get("https://graph.facebook.com/v18.0/{$mediaId}");
            
            $mimeData = $file_type->json();

            $mimeType = $mimeData['mime_type'];

            $extensionMap = [
                'image/jpeg' => 'jpeg',
                'image/jpg' => 'jpg',
                'image/png'  => 'png',
                'image/webp' => 'webp',
                'image/gif'  => 'gif',
            ];
            
            $extension = isset($extensionMap[$mimeType]) ? $extensionMap[$mimeType] : 'jpg';

            $fileName = $mediaId .'.'. $extension;
            $filePath = $saveDir . '/' . $fileName;
            $fileUrl = asset('storage/WhatsappImages/' . $fileName);
            $fullFileUrl = env('BASE_URL') . $fileUrl;

            // Check if the image is already in storage
            if (Storage::exists($filePath)) {
                // Check if the URL is already in the database
                $existingPhotos = explode(',', $findCar->photo_links);
                if (in_array($fullFileUrl, $existingPhotos)) {
                    return response()->json([
                        'message' => 'Image already exists',
                        'file_path' => $fullFileUrl
                    ]);
                }

                // If file exists but URL is not in the database, add it
                $findCar->photo_links = $findCar->photo_links 
                    ? $findCar->photo_links . ',' . $fullFileUrl 
                    : $fullFileUrl;
                $findCar->save();

                return response()->json([
                    'message' => 'Image already exists, URL added to database',
                    'file_path' => $fullFileUrl
                ]);
            }

            // Get media URL from WhatsApp API
            $response = Http::withToken($accessToken)->timeout(300)->get("https://graph.facebook.com/v18.0/{$mediaId}");

            if (!$response->successful()) {
                return response()->json(['error' => 'Failed to retrieve media URL'], 400);
            }

            $mediaUrl = $response->json()['url'] ?? null;
            if (!$mediaUrl) {
                return response()->json(['error' => 'Media URL not found'], 400);
            }

            // Download the image
            $imageResponse = Http::withToken($accessToken)->get($mediaUrl);
            if (!$imageResponse->successful()) {
                return response()->json(['error' => 'Failed to download image'], 400);
            }

            // Save the image if not already stored
            Storage::put($filePath, $imageResponse->body());

            // Append new image path to existing uploads column
            $findCar->photo_links = $findCar->photo_links 
                ? $findCar->photo_links . ',' . $fullFileUrl 
                : $fullFileUrl;
            $findCar->save();

            return response()->json([
                'message' => 'Image downloaded successfully',
                'file_path' => $fullFileUrl
            ]);
        } catch (\Exception $e) {

            $maxExecutionTime = ini_get('max_execution_time');

            return response()->json(['error' => 'Error downloading image: ' . $e->getMessage(), 'max_execution_time' => $maxExecutionTime], 500);
        }
    }
}
