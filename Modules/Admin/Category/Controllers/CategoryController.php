<?php

namespace Modules\Admin\Category\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Admin\Category\Models\Category;

class CategoryController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'status' => 'required|integer|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $category = Category::create([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return response()->json([
            'message' => 'New Category Added Successfully',
            'category' => $category,
        ], status: 201);
    }

    public function index()
    {
        $category = Category::all();

        return response()->json([
            'message' => 'Categorys data retrieved',
            'category' => $category,
        ], 200);
    }

    public function update(Request $request, $id)
    {
        // Debug the request to see what data is coming
        // dd($request->all());

        // Validate request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'status' => 'required|integer|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Find the Category record
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'Category Not Found'], 404);
        }

        // Update the record
        $category->update([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        // Return success response
        return response()->json([
            'message' => 'Category Updated Successfully',
            'category' => $category,
        ], 200);
    }


    public function destroy($id)
    {
        // Find the Category record
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'Category Not Found'], 404);
        }

        // Delete the record
        $category->delete();

        // Return success response
        return response()->json([
            'message' => 'Category Deleted Successfully',
        ], 200);
    }


}
