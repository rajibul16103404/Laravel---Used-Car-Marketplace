<?php

namespace App\Http\Controllers;

use App\Models\UserImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ImageUploadController extends Controller
{
    public function uploadImages(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email',
            'images' => 'required|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $uploadedImages = [];

        // Upload each image
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->storeAs('uploads', $imageName, 'public');
                $uploadedImages[] = asset('storage/uploads/' . $imageName);
            }
        }

        UserImage::create([
            'name' => $request->name,
            'phone'=> '01709015762',
            'email'=> $request->email,
            'image'=> json_encode($uploadedImages) // Convert array to JSON before saving
        ]);
        

        return response()->json([
            'message' => 'Images uploaded successfully',
            'name' => $request->name,
            'email' => $request->email,
            'images' => $uploadedImages
        ], 200);
    }
}
