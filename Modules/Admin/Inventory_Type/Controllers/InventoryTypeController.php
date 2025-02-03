<?php

namespace Modules\Admin\Inventory_Type\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Admin\Inventory_Type\Models\InventoryType;

class InventoryTypeController extends Controller
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

        $inventory_type = InventoryType::create([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return response()->json([
            'message' => 'New Inventory Type Added Successfully',
            'data' => $inventory_type,
        ], status: 201);
    }

    public function index(Request $request)
    {
        // $inventory_type = inventory_type::all();

        // return response()->json([
        //     'message' => 'inventory_type data retrieved',
        //     'data' => $inventory_type,
        // ], 200);

        if($request->page === '0'){
            $perPage =  InventoryType::count();
        }
        else{
            $perPage = $request->input('per_page', 10);
        }

        $data = InventoryType::paginate($perPage);

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
        $inventory_type = InventoryType::find($id);

        // Check if product exists
        if (!$inventory_type) {
            return response()->json([
                'message' => 'Inventory Type not found',
            ], 404);
        }

        return response()->json([
            'message' => 'Inventory Type data retrieved successfully',
            'data' => $inventory_type,
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

        // Find the inventory_type record
        $inventory_type = InventoryType::find($id);

        if (!$inventory_type) {
            return response()->json(['message' => 'Inventory Type Not Found'], 404);
        }

        // Update the record
        $inventory_type->update([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        // Return success response
        return response()->json([
            'message' => 'Inventory Type Updated Successfully',
            'data' => $inventory_type,
        ], 200);
    }


    public function destroy($id)
    {
        // Find the inventory_type record
        $inventory_type = InventoryType::find($id);

        if (!$inventory_type) {
            return response()->json(['message' => 'Inventory Type Not Found'], 404);
        }

        // Delete the record
        $inventory_type->delete();

        // Return success response
        return response()->json([
            'message' => 'Inventory Type Deleted Successfully',
        ], 200);
    }


}
