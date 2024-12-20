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
            'data' => $category,
        ], status: 201);
    }

    public function index(Request $request)
    {
        // $category = Category::all();

        // return response()->json([
        //     'message' => 'Category data retrieved',
        //     'data' => $category,
        // ], 200);

        $perPage = $request->input('per_page', 10);

        $data = Category::paginate($perPage);

        return response()->json([
            'pagination' => [
                'total_count'=>$data->total(),
                'total_page'=>$data->lastPage(),
                'current_page'=>$data->currentPage(),
                'current_page_count'=>$data->count(),
                'next_page' => $data->hasMorePages() ? $data->currentPage()+1 : null,
                'previous_page'=>$data->onFirstPage() ? null : $data->currentPage()
            ],
            'message' => 'Data Retrieved Successfully',
            'data' => $data->items(),
        ],200);
    }

    public function show($id)
    {
        // Find product by ID
        $category = Category::find($id);

        // Check if product exists
        if (!$category) {
            return response()->json([
                'message' => 'Category not found',
            ], 404);
        }

        return response()->json([
            'message' => 'Category data retrieved successfully',
            'data' => $category,
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
            'data' => $category,
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
